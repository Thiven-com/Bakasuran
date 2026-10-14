<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\PaymentGateway;

class PaymentGatewaySeeder extends Seeder
{
    public function run()
    {
        PaymentGateway::create([
            'code' => 'razorpay',
            'name' => 'Razorpay',
            'is_online' => 'yes',
            'status' => 'show',
            'image' => 'media/gateways/razorpay.png',
            'description' => 'Pay securely with Razorpay.',
            'config' => [
                'key_id'    => 'rzp_test_R9GdWcNAde0fOH',
                'secret'    => 'EfDOgPQMM170Rv6ENjAaqsyM',
                'test_mode' => true,
            ],
            'fee_percent' => 2.0,
            'fee_fixed'   => 0,
            'sort_order'  => 1,
        ]);

        PaymentGateway::create([
            'code' => 'cod',
            'name' => 'Cash on Delivery',
            'is_online' => 'no',
            'status' => 'show',
            'image' => 'media/gateways/cod.png',
            'description' => 'Pay with cash on delivery.',
            'config' => [],
            'fee_percent' => 0,
            'fee_fixed'   => 0,
            'sort_order'  => 2,
        ]);
    }
}
