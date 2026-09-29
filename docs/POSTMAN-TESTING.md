# Postman Testing — Piiston Core Flows

## 1. Start the server

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

`0.0.0.0` lets you test from a phone on the same wifi. Use `http://<your-lan-ip>:8000`
as `base_url` if Postman runs on another device.

## 2. Import

| File | What it is |
|---|---|
| `postman/Piiston-Core-Flows.postman_collection.json` | 47 requests across 6 folders |
| `postman_env.json` | Environment with `base_url`, `token`, and all ID variables |

Import the environment **first**, then select it from the environment dropdown (top-right).

## 3. Run order

1. **1. Auth → Login** — captures the token automatically
2. **2. Catalog → Brands / Models** — gives you `brand_id` / `model_id`
3. **3. Add Vehicle → Add vehicle** — saves `vehicle_id`
4. **4. Garage Search → Search garages**, then **Show garage** (saves `branch_id`) and
   **Services of a branch** (saves `service_id`)
5. **5. Book Service → Book appointment** — saves `appointment_id`
6. **6. Send Assistant Alert → Send assistant alert (SOS)** — saves `emergency_id`

Every request that returns an ID writes it back to the environment via its test script,
so you never have to copy IDs by hand.

## Test logins

Seeded users all use the password `password`:

- `client@piiston.com` — VEHICLE_OWNER
- `eric@piiston.com` — VEHICLE_OWNER

You can also register a fresh owner via **Auth → Register** (phone + email must be unique;
use a new value each run or the unique rule rejects it).

## Pre-filled IDs (from the current database)

| Variable | Value | Notes |
|---|---|---|
| `country_id` | 1 | 1=Cameroon, 2=Nigeria, 3=Kenya |
| `brand_id` | 1 | 1=Toyota, 2=Mercedes-Benz, 3=Tesla, 4=BMW |
| `model_id` | 1 | 1=Corolla, 2=Hilux (check `Models for brand` for the rest) |
| `fuel_type_id` | 1 | 1=Petrol, 2=Diesel, 3=Electric |
| `transmission_id` | 1 | 1=Manual, 2=Automatic, 3=CVT |
| `garage_id` | 1 | Toyota Service Center |
| `branch_id` | 1 | Yaoundé Mvan Branch |
| `service_id` | 1 | Premium Oil Change |

## Gotchas when re-running

- **One active SOS per user.** `POST /api/emergency-requests` returns **422** if a request
  is still `REQUESTED/ASSIGNED/ACCEPTED/IN_PROGRESS`. Either use a different user, or call
  **Cancel SOS** first.
- **`vin` and `license_plate` must be unique.** Adding the same vehicle twice returns a
  422 validation error. Change `license_plate` in the environment, or use
  `PM-<timestamp>`.
- **Appointment `branch_id` / `garage_id` are optional.** If omitted the API silently falls
  back to the *first* branch in the database.
- **Appointment requires a vehicle you own** — otherwise **403 Unauthorized vehicle**.

## Fixed bugs (were found while building this, now fixed)

Two pre-existing bugs surfaced during setup. Both are fixed and covered by tests.

1. **`PUT /api/vehicles/{vehicle}` and `DELETE /api/vehicles/{vehicle}` returned 500**
   The routes pointed to `VehicleController@update` / `@destroy`, but neither method
   existed on the controller. Added both, plus `UpdateVehicleRequest` and
   `VehicleService::updateVehicle()` / `deleteVehicle()`. Authorization goes through
   `VehiclePolicy`, so editing someone else's vehicle still returns **403**.

2. **`PUT /api/vehicles/{vehicle}/mileage` returned 500**
   Failed with `NOT NULL constraint failed: vehicle_usage_logs.driver_id`, because
   `VehicleService::updateMileage()` writes a usage log with no `driver_id` and the
   column was `NOT NULL` (it is a fleet-monitoring column, and a private car has no
   fleet driver). Migration `make_driver_id_nullable_on_vehicle_usage_logs_table`
   makes the column nullable.

   Run `php artisan migrate` if your database has not been updated yet.

## Mechanics

The SOS folder includes the mechanic-side steps (accept → service sheet → arrive →
complete). Those need a token belonging to a user with the `MECHANIC` role, so log in
as a mechanic in a second Postman tab/window with its own token, or just verify the
owner-side requests return the expected 403s.
