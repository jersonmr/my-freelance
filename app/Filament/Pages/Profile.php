<?php

namespace App\Filament\Pages;

use App\DataTransferObjects\ProfileData;
use App\Filament\Forms\Components\LocalizedCountrySelect;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\Support\Htmlable;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.profile';

    public function getTitle(): string|Htmlable
    {
        return __('filament/menu.profile');
    }

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
                Section::make(__('filament/pages/profile.user.title'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('filament/pages/profile.user.name'))
                            ->columnSpan(1),
                        TextInput::make('email')
                            ->label(__('filament/pages/profile.user.email'))
                            ->email()
                            ->columnSpan(1)
                            ->unique('users', 'email', ignoreRecord: true),
                    ])
                    ->description(__('filament/pages/profile.user.subtitle')),
                Section::make(__('filament/pages/profile.profile.title'))
                    ->relationship('profile')
                    ->description(__('filament/pages/profile.profile.subtitle'))
                    ->columns(3)
                    ->schema([
                        TextInput::make('username')
                            ->label(__('filament/pages/profile.profile.username'))
                            ->autofocus()
                            ->columnSpan(1)
                            ->unique('profiles', 'username', ignoreRecord: true),
                        Grid::make()
                            ->columns(3)
                            ->schema([
                                Textarea::make('about')
                                    ->label(__('filament/pages/profile.profile.about'))
                                    ->rows(3)
                                    ->columnSpan(2),
                            ]),
                    ]),
                Section::make(__('filament/pages/profile.contact.title'))
                    ->relationship('profile')
                    ->description(__('filament/pages/profile.contact.subtitle'))
                    ->columns([
                        'sm' => 4,
                        'xl' => 6,
                    ])
                    ->schema([
                        LocalizedCountrySelect::make('country')
                            ->label(__('filament/pages/profile.contact.country'))
                            ->searchable()
                            ->columnSpan(2),
                        Grid::make()
                            ->columns(3)
                            ->schema([
                                TextInput::make('city')
                                    ->label(__('filament/pages/profile.contact.city')),
                                TextInput::make('state')
                                    ->label(__('filament/pages/profile.contact.state')),
                                TextInput::make('zip')
                                    ->label(__('filament/pages/profile.contact.zip')),
                            ]),
                        Toggle::make('notifications')
                            ->label(__('filament/pages/profile.contact.notification')),
                    ]),
            ])
            ->statePath('data')
            ->model(auth()->user());
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('Update')
                ->label(__('filament/pages/profile.actions.update'))
                ->color('primary')
                ->submit('Update'),
        ];
    }

    public function update()
    {
        $attributes = $this->form->getState();

        $user = auth()->user();

        $user->update([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
        ]);

        $profileData = ProfileData::from($this->data['profile']);

        $user->profile->update($profileData->toArray());

        $this->data['profile']['avatar'] = [$profileData->avatar];

        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
