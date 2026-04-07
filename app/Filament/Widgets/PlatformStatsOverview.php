<?php

namespace App\Filament\Widgets;

use App\Models\Equipment;
use App\Models\FinancialLedger;
use App\Models\InFlightOrder;
use App\Models\LeaseContract;
use App\Models\Maneuver;
use App\Models\Procurement;
use App\Support\Money;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Schema;

class PlatformStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $ordersTotal = InFlightOrder::query()->count();
        $ordersPending = InFlightOrder::query()->where('status', 'pending')->count();
        $ordersShipped = InFlightOrder::query()->where('status', 'shipped')->count();
        $ordersDelivered = InFlightOrder::query()->where('status', 'delivered')->count();

        $leasesActive = LeaseContract::query()->where('status', 'active')->count();
        $leasesClosed = LeaseContract::query()->where('status', 'closed')->count();
        $leasesPendingReview = LeaseContract::query()->where('status', 'pending_review')->count();

        $damagesTotal = Maneuver::query()->where('type', 'damage')->count();
        $maneuversFlagged = Maneuver::query()->where('status', 'flagged_review')->count();

        $equipmentTotal = Equipment::query()->count();
        $stockTotal = (int) Equipment::query()->sum('total_stock');
        $stockAvailable = (int) Equipment::query()->sum('available_stock');

        $salesTotal = (float) FinancialLedger::query()->where('type', 'invoice')->sum('total_value');
        $outstandingBalance = (float) FinancialLedger::query()->sum('balance');
        $maintenanceFees = (float) FinancialLedger::query()->where('type', 'maintenance')->sum('total_value');

        $procurementsTotal = Schema::hasTable('procurements')
            ? Procurement::query()->count()
            : 0;

        $procurementsPending = Schema::hasTable('procurements')
            ? Procurement::query()->where('status', 'pending')->count()
            : 0;

        return [
            Stat::make('Orders', number_format($ordersTotal))
                ->description(number_format($ordersPending).' pending • '.number_format($ordersShipped).' shipped • '.number_format($ordersDelivered).' delivered')
                ->icon('heroicon-o-truck')
                ->color($ordersPending > 0 ? 'warning' : 'success'),

            Stat::make('Ventes (Factures)', Money::format($salesTotal, env('APP_CURRENCY', 'RWF')))
                ->description('Frais de maintenance: '.Money::format($maintenanceFees, env('APP_CURRENCY', 'RWF')))
                ->icon('heroicon-o-currency-dollar')
                ->color('info'),

            Stat::make('Solde restant', Money::format($outstandingBalance, env('APP_CURRENCY', 'RWF')))
                ->description('Tous les journaux')
                ->icon('heroicon-o-exclamation-triangle')
                ->color($outstandingBalance > 0 ? 'danger' : 'success'),

            Stat::make('Leases', number_format($leasesActive))
                ->description(number_format($leasesPendingReview).' pending review • '.number_format($leasesClosed).' closed')
                ->icon('heroicon-o-document-text')
                ->color($leasesPendingReview > 0 ? 'warning' : 'success'),

            Stat::make('Damages', number_format($damagesTotal))
                ->description(number_format($maneuversFlagged).' flagged for review')
                ->icon('heroicon-o-wrench-screwdriver')
                ->color(($damagesTotal > 0 || $maneuversFlagged > 0) ? 'warning' : 'success'),

            Stat::make('Inventory', number_format($equipmentTotal).' items')
                ->description(number_format($stockAvailable).' available • '.number_format($stockTotal).' total stock')
                ->icon('heroicon-o-cube')
                ->color($stockAvailable === 0 && $stockTotal > 0 ? 'danger' : 'info'),

            Stat::make('Procurements', number_format($procurementsTotal))
                ->description(number_format($procurementsPending).' pending')
                ->icon('heroicon-o-clipboard-document-check')
                ->color($procurementsPending > 0 ? 'warning' : 'success'),
        ];
    }
}

