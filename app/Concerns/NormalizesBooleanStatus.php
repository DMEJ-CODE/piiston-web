<?php

namespace App\Concerns;

trait NormalizesBooleanStatus
{
    /**
     * Values that explicitly mean "disabled".
     *
     * @var list<string>
     */
    protected array $falsyStatusValues = [
        '', '0', 'false', 'no', 'off', 'null',
        'inactive', 'disabled', 'hidden', 'draft', 'archived',
        'suspended', 'banned', 'deleted',
    ];

    /**
     * Coerce the status attribute to a real boolean on write.
     *
     * Several tables back "status" with a boolean column, but string values such
     * as 'ACTIVE' or 'active' were sometimes assigned to it. Because a boolean
     * cast only normalises on read, those strings were persisted verbatim and
     * rows stopped matching queries such as where('status', true). Normalising
     * on write keeps the stored value aligned with the column definition.
     */
    public function setStatusAttribute(mixed $value): void
    {
        if ($value === null) {
            $this->attributes['status'] = null;

            return;
        }

        if (is_string($value)) {
            $normalized = strtolower(trim($value));
            $this->attributes['status'] = in_array($normalized, $this->falsyStatusValues, true) ? false : true;

            return;
        }

        $this->attributes['status'] = (bool) $value;
    }
}
