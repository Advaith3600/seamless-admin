<?php

namespace Advaith\SeamlessAdmin\Traits;

trait SeamlessAdmin
{
    public bool $hasAdminPage = true;

    public function adminIndexFields(): array
    {
        return array_diff($this->getFillable(), $this->getHidden());
    }

    public function adminOnEdit(array $fields): array
    {
        return $fields;
    }

    public function adminOnCreate(array $fields): array
    {
        return $fields;
    }

    public function adminEdited(): void {}
    public function adminCreated(): void {}
}
