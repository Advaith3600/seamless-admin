<?php

namespace Advaith\SeamlessAdmin\Traits;

trait SeamlessAdmin
{
    /**
     * Private property which determines the existence of admin page
     * Overriding the value to false will not affect anything
     * To remove the model from being displayed from the admin page remove this trait
     *
     * @var bool
     */
    public bool $hasAdminPage = true;

    /**
     * Determines the information shown on the index page of the model listing
     *
     * @return array
     */
    public function adminIndexFields(): array
    {
        return array_diff($this->getFillable(), $this->getHidden());
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
}
