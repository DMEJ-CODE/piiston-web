# Piiston — UML Diagrams

PlantUML source for the Piiston platform: **Flutter mobile client**
(`/home/ericd/StudioProjects/piisston_v1`) + **Laravel 13 backend & web front**
(`/home/ericd/Piiston`).

Render with PlantUML (any of):

```bash
# CLI
java -jar plantuml.jar docs/uml/*.puml

# VS Code: install "PlantUML" extension (jebbs)
# Web:  https://www.plantuml.com/plantuml/  (paste / encode)
```

Legend: `<color:#b00>` = defect, dead code, or a bypass found during analysis.
`<color:#080>` = a guard / side effect that is actually enforced.

---

## 1. `01-state-machine.puml` — Mobile state machines

| Machine | Trigger surface | Source |
|---|---|---|
| **Mobile Auth Session** | 9 states: Launching → Welcome → Register → PhoneEntry → OtpSent → RoleSelect → Home, plus `Expired` on 401 | `main.dart`, `loading.dart`, `auth/*`, `auth_services.dart` |
| **Mobile SOS** | 8 states mirroring `EmergencyRequest.status` | `emergency_service.dart`, `emergency_assistance.dart`, `mechanic_emergency_hub.dart` |
| **Call Session** | Ringing → Active → Ringing, purely local | `call_service.dart` |
| **Connectivity** | Online ⇄ Offline with a 10s recovery probe | `api_service.dart` |

Auth gating is **token presence only** — no expiry check, no role check, no
onboarding check at the splash screen.

## 2. `02-state-machine-server.puml` — Server state machines

24 machines extracted from real transition code, grouped by domain:

- **Repair:** `RepairOrder` (12 declared + 1 undeclared `OPEN`), `RepairEstimate`, `RepairTask`
- **Booking:** `GarageAppointment`, `VehicleCheckIn`, `ServiceRequest`
- **SOS:** `EmergencyRequest`, `TrackingSession`
- **Marketplace:** `Order` (2 orthogonal fields), `Delivery`, `PartRequest`, `PartQuote`, `PurchaseOrder`
- **Finance:** `PaymentTransaction`, `Wallet`, `WalletTransaction`, `UserSubscription`
- **Support:** `WarrantyClaim`, `Incident`
- **Comms:** `Message`, `MessageStatus`, `UserPresence`, `Notification`
- **Orphans:** 10 entities that declare a `status` column with **zero** transition code

`PaymentTransaction` is the best-formed machine: 3 states, terminal `SUCCESS`,
idempotency-guarded and HMAC-signature-guarded. Everything else has a
documented gap.

## 3. `03-package-diagram.puml` — Package structure

Three top-level packages:

- **MOBILE** — `lib/` split into root, `auth`, routing, `models`, `services`
  (9 sub-packages: core, identity, vehicle, repair-workflow, marketplace,
  finance, comms, ai, platform-extras), `pages` (7 sub-packages, ~100 screens),
  `perso_widget`, `theme`
- **BACKEND** — `bootstrap`, `routes`, middleware, ~130 controllers (v1 /
  legacy / web), resources, 20 service sub-packages, 13 repository pairs,
  **≈300 Eloquent models** across 20 domain namespaces, 7 policies, AI
  adapters, search providers, events, jobs, 16 Livewire screens, `config`,
  `database`, `resources`
- **INFRA / EXTERNAL** — DB, cache, queue, object storage, Redis; Gemini,
  OpenAI, OpenRouter, NotchPay, Reverb, FCM, mail, logs

## 4. `04-component-diagram.puml` — Components & interfaces

Six interfaces (`IMobileApi`, `IWebApi`, `IRealtime`, `IPush`, `IPayment`,
`IAiProvider`) and the components that implement them, from the Flutter
service layer through the Laravel controller/service/repository stack out to
Reverb, NotchPay, FCM and the AI adapters.

## 5. `05-class-diagram.puml` — Class diagram

Domain model with multiplicities and stereotypes, covering all 20 backend model
namespaces plus the Flutter `lib/models` enums, the DTOs, the core services
(`ApiService`, `AuthService`, `MessagingService`, `CallService`, …) and the
page classes that consume them.

## 6. `06-deployment-diagram.puml` — Deployment

Client devices → TLS edge → `web` / `api` / `ws` (Reverb) / `queue workers` /
`scheduler` nodes, the RDBMS + Redis + object-storage data tier, and every
external SaaS with its real env-var keys.

---

## Findings surfaced by the diagrams

**Security**

1. `api/internal-admin/*` is guarded by `auth:sanctum` only — no `admin`
   middleware. Any authenticated user can suspend accounts, approve business
   verifications, write platform settings, and toggle feature flags.
2. `bootstrap/app.php` calls neither `statefulApi()` nor `throttleApi()`, so the
   entire 328-route API is unthrottled — including OTP send, login and AI chat.
3. `.env.example` sets `NOTCHPAY_CALLBACK_URL` to `/api/v1/subscriptions/notchpay/callback`.
   The `/v1` prefix was removed from routing; the live route is
   `/api/subscriptions/notchpay/callback`. Subscription payments never confirm.
4. `config/services.php` has no `openai` key. `OpenAIAdapter` only works via the
   `env()` fallback, which returns `null` once config is cached in production.
5. `config/reverb.php` sets `allowed_origins => ['*']`.
6. `SendPushNotificationJob:79` has the FCM `Http::post` **commented out** — push
   is inert, which also breaks inbound call notifications.
7. Client ships a dev bypass: `AuthService.verifyOtp` returns success for code
   `123456` regardless of the server response. Pusher `apiKey` is hardcoded with
   `useTLS: false`.

**Correctness**

8. `Vehicle` has no `maintenances()` relation, yet `RepairService:159` and
   `EmergencyRequestController:255` call it — both paths fatal.
9. `RepairOrder` defines `garageCustomer()` but the codebase reads
   `$repair->customer`; two customer notifications silently never fire.
10. `RepairService::recordPayment()` is called by `Web\RepairController:247` but
    does not exist.
11. `App\Models\Garages\GaragePurchaseOrder` is imported by
    `PurchaseOrderService` and `Garage\PurchaseOrderController` but does not exist.
12. `PartRequest::quotes()` is eager-loaded in two controllers but is undefined.
13. `UserRole.fromString` cannot produce `seller` or `mechanic` — the seller and
    mechanic bottom-nav tab sets, `SellerDashboard` and the seller-onboarding
    redirect are unreachable code.
14. `MarketplaceService.placeOrder()` writes `order_status = 'CREATED'`, which is
    absent from the seller `updateStatus` whitelist and from the dashboard's
    pending count — those orders can never advance.
15. Marketplace `payment_status` is never set to `PAID` anywhere; the payment
    chain creates a `PaymentTransaction` but never links it back to the order.
16. `product_listings.status` and `seller_profiles.status` are boolean columns that
    receive the string `'active'`; only `GarageCompany` / `GarageBranch` use the
    `NormalizesBooleanStatus` concern.

**Operations**

17. `routes/console.php` has no `Schedule::` and `bootstrap/app.php` calls no
    `withSchedule()`, so `system-agent:run` never fires without an external cron.
18. `BROADCAST_CONNECTION` defaults to `null` — all four broadcast events no-op
    unless set to `reverb`; `.env.example` still says `log`.
19. `ApiService` hardcodes `192.168.251.219` over plain HTTP with no
    `dart-define` indirection.
20. Messaging, search and finance each have a legacy and a v1 route set hitting
    the same models; `permission:` middleware is registered but never applied.
