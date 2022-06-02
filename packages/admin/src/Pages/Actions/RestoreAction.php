<?php

namespace Filament\Pages\Actions;

use Filament\Support\Actions\Concerns\CanNotify;
use Filament\Support\Actions\Concerns\HasBeforeAfterCallbacks;

class RestoreAction extends Action
{
    use CanNotify;
    use HasBeforeAfterCallbacks;

    protected function setUp(): void
    {

        $action = $this;

        // @TODO: Set translation
        // $this->label(__('tables::pages.actions.force_delete.label'));
        $this->label('Restore');

        $this->modalButton(static fn (RestoreAction $action): string => $action->getLabel());

        // @TODO: Set translation
        // $this->notificationMessage(__('tables::pages.actions.force_delete.messages.deleted'));
        $this->notificationMessage('Restored');

        $this->visible(fn () => method_exists($this->getLivewire()->record, 'trashed') && $this->getLivewire()->record->trashed());

        $this->requiresConfirmation();

        $this->modalHeading(fn () => __('filament::resources/pages/edit-record.actions.delete.modal.heading', ['label' => 'BLA']));
        $this->modalSubheading(__('filament::resources/pages/edit-record.actions.delete.modal.subheading'));
        $this->modalButton(__('filament::resources/pages/edit-record.actions.delete.modal.buttons.delete.label'));
        $this->keyBindings(['mod+z']);

        $this->action(static function () use ($action) {

            $record = $action->getLivewire()->record;

            $action->evaluate($action->beforeCallback, ['record' => $record]);

            $record->restore();

            $action->notify();

            return $action->evaluate($action->afterCallback, ['record' => $record]);
        });

        parent::setUp();
    }

}
