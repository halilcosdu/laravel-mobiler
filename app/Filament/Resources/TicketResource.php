<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\Widgets\TicketStatsChart;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group as TableGroup;
use Filament\Tables\Table;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationGroup = 'Management';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): string
    {
        return Ticket::query()->where('is_resolved', false)->count();
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'danger';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('device_id')
                    ->relationship('device', 'client_device_code')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('message')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_resolved')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups([
                TableGroup::make('device_id')
                    ->label('Devices')
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->copyable()
                    ->badge()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_resolved')
                    ->boolean(),
                Tables\Columns\IconColumn::make('device.is_premium')
                    ->label('Premium')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->since()
                    ->sortable(),
            ])->defaultSort('created_at', 'desc')
            ->filters(
                [
                    Tables\Filters\Filter::make('is_premium')
                        ->toggle()
                        ->label('Show only resolved')
                        ->query(function ($query) {
                            $query->where('is_resolved', true);
                        }),
                    Tables\Filters\Filter::make('is_banned')
                        ->toggle()
                        ->label('Show only Premium')
                        ->query(function ($query) {
                            $query->whereHas('device', function ($query) {
                                $query->where('is_premium', true);
                            });
                        }),
                ]
            )
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()->slideOver(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
                Section::make('Device Information')
                    ->description('This information is used to help us better understand our users')
                    ->icon('heroicon-o-device-tablet')
                    ->collapsible()
                    ->columnSpanFull()
                    ->schema([
                        Group::make()
                            ->columns(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Name'),
                                TextEntry::make('device.client_device_code')
                                    ->copyable()
                                    ->copyMessage('Device Code copied to clipboard.')
                                    ->badge()
                                    ->label('Device Code'),

                                TextEntry::make('email')
                                    ->label('Email')
                                    ->copyable()
                                    ->copyMessage('Email copied to clipboard.')
                                    ->badge(),
                                IconEntry::make('is_resolved')
                                    ->label('Resolved')
                                    ->boolean(),
                                IconEntry::make('device.is_banned')
                                    ->label('Banned')
                                    ->boolean(),
                                IconEntry::make('device.is_premium')
                                    ->boolean()
                                    ->label('Premium'),
                            ]),
                    ]),
                Section::make('Ticket Details')
                    ->description('This information is used to help us better understand our users.')
                    ->icon('heroicon-s-command-line')
                    ->collapsible()
                    ->columnSpanFull()
                    ->schema([

                        Group::make([
                            TextEntry::make('created_at')
                                ->color('danger')
                                ->label('Date'),
                            TextEntry::make('updated_at')
                                ->dateTime()
                                ->since()
                                ->color('success')
                                ->label('Last Updated Date'),

                        ])->columns(2),

                        TextEntry::make('note')
                            ->default('-')
                            ->color('warning')
                            ->markdown(),

                        TextEntry::make('message')
                            ->markdown(),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'view' => Pages\ViewTicket::route('/{record}'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            TicketStatsChart::class,
        ];
    }
}
