<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.profile';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            auth()->user()->attributesToArray()
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                         TextInput::make('username')
                             ->label(__('Username'))
                             ->autofocus(),
                         Textarea::make('about')
                             ->label(__('About'))
                             ->rows(3),
                         FileUpload::make('avatar')
                             ->label(
                                 __('Avatar'))
                             ->avatar()
                             ->imageEditor()
                             ->circleCropper(),
                         TextInput::make('name')
                             ->label(__('Name'))
                             ->required(),
                         TextInput::make('email')
                             ->label(__('Email'))
                             ->required(),
                     ])
            ->statePath('data')
            ->model(auth()->user());
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('Update')
                ->label(__('Update'))
                ->color('primary')
                ->submit('Update'),
        ];
    }

    public function update()
    {
        dd($this->form->getState());
        auth()->user()->update(
            $this->form->getState()
        );
    }
}
