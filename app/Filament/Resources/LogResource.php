<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogResource\Pages;
use App\Models\Log;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use HalilCosdu\LogWeaver\Facades\LogWeaver;
use Illuminate\Database\Eloquent\Model;
use ValentinMorice\FilamentJsonColumn\FilamentJsonColumn;

class LogResource extends Resource
{
    protected static ?string $model = Log::class;

    protected static ?string $navigationGroup = 'Management';

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('device_id')
                    ->numeric(),
                Forms\Components\TextInput::make('level')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('log_resource')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                FilamentJsonColumn::make('path')
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('device.client_device_code')
                    ->copyable()
                    ->default('-')
                    ->badge(),
                Tables\Columns\TextColumn::make('level')
                    ->color(fn (Log $record) => match ($record->level) {
                        'info' => 'success',
                        'warning' => 'warning',
                        'error', 'critical' => 'danger',
                        default => 'primary',
                    })
                    ->badge(),
                Tables\Columns\TextColumn::make('log_resource')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('path')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('download_log')
                    ->label('Download')
                    ->color('warning')
                    ->icon('heroicon-s-cloud-arrow-down')
                    ->action(fn (Log $record) => LogWeaver::disk($record->disk)->download($record->getRawOriginal('path'))),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->after(fn (Log $record) => LogWeaver::disk($record->disk)->delete($record->getRawOriginal('path'))),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLogs::route('/'),
            'create' => Pages\CreateLog::route('/create'),
            'edit' => Pages\EditLog::route('/{record}/edit'),
        ];
    }
}
