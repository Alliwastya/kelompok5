<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\MessageThread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::factory()->create(['is_admin' => true]);
        
        // Create regular user
        $this->user = User::factory()->create(['is_admin' => false]);
    }

    /**
     * Test admin dashboard is accessible for admin users
     */
    public function test_admin_dashboard_accessible_for_admin(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Dashboard')
            ->has('totalRevenue')
            ->has('monthlyRevenue')
            ->has('totalOrders')
            ->has('totalMessages')
            ->has('salesChart')
            ->has('recentOrders')
            ->has('recentMessages')
        );
    }

    /**
     * Test admin dashboard is not accessible for non-admin users
     */
    public function test_admin_dashboard_not_accessible_for_non_admin(): void
    {
        $response = $this->actingAs($this->user)->get('/admin');

        $response->assertStatus(302); // Redirect
        $response->assertRedirect('/dashboard');
    }

    /**
     * Test admin dashboard requires authentication
     */
    public function test_admin_dashboard_requires_authentication(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Test dashboard displays today's revenue
     */
    public function test_dashboard_displays_todays_revenue(): void
    {
        // Create orders for today
        Order::factory()->count(3)->create([
            'total_amount' => 150000,
            'created_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertInertia(fn ($page) => $page
            ->where('totalRevenue', 450000) // 3 orders × 150000
        );
    }

    /**
     * Test dashboard displays monthly revenue
     */
    public function test_dashboard_displays_monthly_revenue(): void
    {
        // Create orders for this month
        Order::factory()->count(5)->create([
            'total_amount' => 200000,
            'created_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertInertia(fn ($page) => $page
            ->where('monthlyRevenue', 1000000) // 5 orders × 200000
        );
    }

    /**
     * Test dashboard displays today's orders count
     */
    public function test_dashboard_displays_todays_orders_count(): void
    {
        Order::factory()->count(7)->create(['created_at' => now()]);

        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertInertia(fn ($page) => $page
            ->where('totalOrders', 7)
        );
    }

    /**
     * Test dashboard displays open messages count
     */
    public function test_dashboard_displays_open_messages_count(): void
    {
        MessageThread::factory()->count(3)->create(['status' => 'open']);
        MessageThread::factory()->count(2)->create(['status' => 'replied']);

        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertInertia(fn ($page) => $page
            ->where('totalMessages', 3) // Only open messages
        );
    }

    /**
     * Test dashboard displays recent orders
     */
    public function test_dashboard_displays_recent_orders(): void
    {
        $orders = Order::factory()->count(10)->create();

        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertInertia(fn ($page) => $page
            ->has('recentOrders', 10)
        );
    }

    /**
     * Test dashboard displays recent messages
     */
    public function test_dashboard_displays_recent_messages(): void
    {
        $messages = MessageThread::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertInertia(fn ($page) => $page
            ->has('recentMessages', 5)
        );
    }

    /**
     * Test sales chart data is generated correctly
     */
    public function test_sales_chart_data_generated_correctly(): void
    {
        // Create orders on different dates this month
        Order::factory()->create([
            'total_amount' => 100000,
            'created_at' => now()->startOfMonth(),
        ]);

        Order::factory()->create([
            'total_amount' => 200000,
            'created_at' => now()->startOfMonth()->addDay(),
        ]);

        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertInertia(fn ($page) => $page
            ->has('salesChart', 2)
            ->where('salesChart.0.total', 100000)
            ->where('salesChart.0.count', 1)
            ->where('salesChart.1.total', 200000)
            ->where('salesChart.1.count', 1)
        );
    }

    /**
     * Test revenue from previous month is not included
     */
    public function test_revenue_from_previous_month_not_included(): void
    {
        // Create order this month
        Order::factory()->create([
            'total_amount' => 500000,
            'created_at' => now(),
        ]);

        // Create order last month
        Order::factory()->create([
            'total_amount' => 300000,
            'created_at' => now()->subMonth(),
        ]);

        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertInertia(fn ($page) => $page
            ->where('monthlyRevenue', 500000) // Only this month
        );
    }
}
