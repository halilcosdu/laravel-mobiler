<?php

namespace App\Filament\Resources;

use App\Enums\OSTypes;
use App\Filament\Resources\DeviceResource\Pages;
use App\Filament\Resources\DeviceResource\Widgets\DeviceStatusChart;
use App\Filament\Resources\DeviceResource\Widgets\DeviceStatusOverviewChart;
use App\Models\Device;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group as TableGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DeviceResource extends Resource
{
    protected static ?string $model = Device::class;

    public static function canCreate(): bool
    {
        return false;
    }

    protected static ?string $navigationGroup = 'Application';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'client_device_code';

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Platform' => str($record->os_type->value)->title(),
            'Premium' => $record->is_premium ? 'Premium' : 'Free',
        ];
    }

    public static function getNavigationBadge(): string
    {
        return Device::query()->where('is_premium', true)->count();
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('is_blocked')
                    ->required(),
                Forms\Components\Toggle::make('is_premium')
                    ->required(),
                Forms\Components\TextInput::make('timezone')
                    ->required()
                    ->maxLength(255)
                    ->default(config('app.timezone')),
                Forms\Components\TextInput::make('os_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('os_version')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('device_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('device_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('app_version')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('client_device_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('language_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('country_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups([
                TableGroup::make('is_premium')
                    ->label('Premium')
                    ->collapsible(),
                TableGroup::make('created_at')
                    ->label('Created At')
                    ->collapsible()
                    ->date(),
            ])
            ->filtersTriggerAction(function ($action) {
                return $action->button()->label('Filters');
            })
            ->columns([
                Tables\Columns\ToggleColumn::make('is_blocked')
                    ->label('Blocked')
                    ->onIcon('heroicon-m-bolt-slash')
                    ->offIcon('heroicon-m-bolt')
                    ->onColor('danger')
                    ->offColor('success')
                    ->afterStateUpdated(function ($record, $state) {
                        Notification::make('is_blocked')
                            ->title($state ? 'Device Blocked.' : 'Device Activated.')
                            ->{$state ? 'danger' : 'success'}()
                            ->send();
                    }),
                Tables\Columns\IconColumn::make('is_premium')
                    ->label('Premium')
                    ->sortable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('client_device_code')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Client Device Code copied to clipboard.')
                    ->badge(),
                Tables\Columns\TextColumn::make('timezone')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('os_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('device_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('device_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('app_version')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('language_code')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('country_code')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('notifications_enabled')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ToggleColumn::make('email_notifications_enabled')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('notifications_email')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('os_type')
                    ->label('OS Type')
                    ->searchable()
                    ->multiple()
                    ->options(OSTypes::getSelect()),

                Tables\Filters\Filter::make('is_premium')
                    ->toggle()
                    ->label('Show only Premium')
                    ->query(function ($query) {
                        $query->where('is_premium', true);
                    }),
                Tables\Filters\Filter::make('is_blocked')
                    ->toggle()
                    ->label('Show only Blocked')
                    ->query(function ($query) {
                        $query->where('is_blocked', true);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Record Information')
                    ->description('This information is used to help us better understand our users.')
                    ->icon('heroicon-o-device-tablet')
                    ->collapsible()
                    ->columnSpanFull()
                    ->schema([
                        Group::make()
                            ->columns(2)
                            ->schema([
                                TextEntry::make('id')
                                    ->badge()
                                    ->label('ID')
                                    ->copyable()
                                    ->copyMessage('ID copied to clipboard.'),

                                TextEntry::make('client_device_code')
                                    ->label('Client Device Code')
                                    ->copyable()
                                    ->copyMessage('Client Device Code copied to clipboard.')
                                    ->badge(),
                            ]),
                    ]),
                Section::make('Device Information')
                    ->description('This information is used to help us better understand our users.')
                    ->icon('heroicon-o-device-tablet')
                    ->collapsible()
                    ->columnSpanFull()
                    ->schema([
                        Group::make()
                            ->columns(4)
                            ->schema([
                                IconEntry::make('is_blocked')
                                    ->label('Blocked')
                                    ->boolean(),
                                IconEntry::make('is_premium')
                                    ->boolean()
                                    ->label('Premium'),
                                TextEntry::make('timezone'),
                                TextEntry::make('os_type'),
                                TextEntry::make('os_version'),
                                TextEntry::make('device_name'),
                                TextEntry::make('device_type'),
                                TextEntry::make('app_version'),
                                TextEntry::make('language_code'),
                                TextEntry::make('country_code'),
                                TextEntry::make('created_at'),
                                TextEntry::make('updated_at'),

                            ]),
                    ]),
                Section::make('User Information')
                    ->description('This information is used to help us better understand our users.')
                    ->icon('heroicon-o-user')
                    ->collapsible()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('name')->default('-'),
                        TextEntry::make('email')->default('-'),
                        TextEntry::make('phone_number')->default('-'),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            DeviceStatusOverviewChart::class,
            DeviceStatusChart::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDevices::route('/'),
            'create' => Pages\CreateDevice::route('/create'),
            'edit' => Pages\EditDevice::route('/{record}/edit'),
            'view' => Pages\ViewDevice::route('/{record}'),
        ];
    }
}
