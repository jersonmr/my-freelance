<?php

namespace App\Filament\Pages;

use App\DataTransferObjects\ProfileData;
use App\DataTransferObjects\UserData;
use App\Filament\Forms\Components\LocalizedCountrySelect;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
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

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.profile';
    protected static ?string $title = '';

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
                         Section::make('User Information')
                             ->columns(2)
                             ->schema([
                                          TextInput::make('name')
                                              ->label(__('Name'))
                                              ->columnSpan(1),
                                          TextInput::make('email')
                                              ->label(__('Email'))
                                              ->email()
                                              ->columnSpan(1)
                                              ->unique('users', 'email', ignoreRecord: true),
                                      ])
                             ->description('This is the personal user information.'),
                         Section::make('Profile')
                             ->description('This information will be displayed publicly so be careful what you share.')
                             ->columns(3)
                             ->schema([
                                          TextInput::make('profile.username')
                                              ->label(__('Username'))
                                              ->autofocus()
                                              ->columnSpan(1)
                                              ->unique('profiles', 'username', ignoreRecord: true),
                                          Grid::make()
                                              ->columns(3)
                                              ->schema([
                                                           Textarea::make('profile.about')
                                                               ->label(__('About'))
                                                               ->rows(3)
                                                               ->columnSpan(2),
                                                       ]),
                                          FileUpload::make('profile.avatar')
                                              ->multiple(false)
                                              ->label(
                                                  __('Avatar'))
                                              ->maxSize(1024 * 1024 * 2)
                                              ->avatar()
                                              ->imageEditor()
                                              ->circleCropper()
                                              ->directory('avatars')
                                              ->columnSpan(1),
                                      ]),
                         Section::make('Contact Information')
                             ->relationship('profile')
                             ->description('Use a permanent address where you can receive mail.')
                             ->columns([
                                           'sm' => 4,
                                           'xl' => 6,
                                       ])
                             ->schema([

                                          LocalizedCountrySelect::make('country')
                                              ->label(__('Country'))
                                              ->searchable()
                                              ->columnSpan(2),
                                          Grid::make()
                                              ->columns(3)
                                              ->schema([
                                                           TextInput::make('city')
                                                               ->label(__('City')),
                                                           TextInput::make('state')
                                                               ->label(__('State / Province')),
                                                           TextInput::make('zip')
                                                               ->label(__('ZIP / Postal code')),
                                                       ]),
                                          Toggle::make('notifications')
                                              ->label(__('Receive notifications')),
                                      ]),
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
        dd($this->data);
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
