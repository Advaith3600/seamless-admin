<?php

namespace Advaith\SeamlessAdmin\Traits;

trait SeamlessAdmin
{
    /**
     *  Whether the key name should be visible for admins
     *  Default: true
     */
    public bool $keyNameVisibleForAdmins = true;

    /**
     * Determines the information shown on the index page of the model listing
     *
     * @return array
     */
    public function adminIndexFields(): array
    {
        $fillable = $this->getFillable();
        if ($this->keyNameVisibleForAdmins && !in_array($this->getKeyName(), $fillable)) {
            array_unshift($fillable, $this->getKeyName());
        }

        return array_diff($fillable, $this->getHidden());
    }

    /**
     * Process the information being stored while editing an existing entry in the database
     *
     * @param array $fields
     * @return array
     */
    public function adminOnEdit(array $fields): array
    {
        return $fields;
    }

    /**
     * Process the information being stored while creating a new entry in the database
     *
     * @param array $fields
     * @return array
     */
    public function adminOnCreate(array $fields): array
    {
        return $fields;
    }

    /**
     * Hook fired after an entry is edited in the database
     *
     * @return void
     */
    public function adminEdited(): void
    {
        //
    }

    /**
     * Hook fired after an entry is created in the database
     *
     * @return void
     */
    public function adminCreated(): void
    {
        //
    }

    /**
     * Whether the logged-in user has privilege to access the model
     * Disabling this will restrict the user from accessing the whole model functionalities
     *
     * @return bool
     */
    public function adminCanAccessIndex(): bool
    {
        return true;
    }

    /**
     * Whether the logged-in user has privilege to access the create option
     *
     * @return bool
     */
    public function adminCanAccessCreate(): bool
    {
        return true;
    }

    /**
     * Whether the logged-in user has privilege to access the edit option
     *
     * @return bool
     */
    public function adminCanAccessEdit(): bool
    {
        return true;
    }

    /**
     * Whether the logged-in user has privilege to access the delete option
     *
     * @return bool
     */
    public function adminCanAccessDelete(): bool
    {
        return true;
    }

    /**
     * Convert the model instance to a string
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->{$this->getFillable()[0]};
    }
}
