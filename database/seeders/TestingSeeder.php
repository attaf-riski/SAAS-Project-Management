<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory;

class TestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        // Generate 10 user records
        for ($i = 0; $i < 10; $i++) {
            $roleId = rand(3, 4);
            $userData = [
                'id_role' => $roleId, // Assuming 5 roles exist (adjust if different)
                'fullname' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('secret'), // Replace with your password hashing logic
                'address' => $faker->address,
                'state' => $faker->state,
                'city' => $faker->city,
                'region' => $faker->country,
                'postal_code' => $faker->postcode,
                'profession' => $faker->jobTitle,
                'experience_level' => rand(1, 5), // Adjust range for experience level
                'organization' => $faker->company,
                'photo_profile' => 'defaultProfile.png',
                'status' => 'active', // Adjust default status if needed
            ];

            $planId = ($roleId === 3) ? 1 : 2;
            $userId = DB::table('users')->insertGetId($userData);
            // Insert subscription record based on role
            $subscriptionData = [
                'id_user' => $userId,
                'id_plan' => $planId,
                'duration' => 365, // Adjust duration if needed
                'status' => 'ACTIVE',
                'start_date' => date('Y-m-d'),
                'end_date' => (date('Y-m-d', strtotime('+30 days'))),
            ];

            DB::table('subscriptions')->insert($subscriptionData);
        }

        // Generate 10 client records for user with ID 2
        for ($i = 0; $i < 10; $i++) {
            $clientData = [
                'user_id' => 2, // Replace with your actual user ID
                'name' => $faker->company,
                'address' => $faker->address,
                'no_telp' => $faker->phoneNumber,
                'email' => $faker->safeEmail,
                // Omitting state, city, region, and postal_code (adjust if needed)
            ];

            // Include additional columns if required based on your table structure
             $clientData['state'] = $faker->state;
             $clientData['city'] = $faker->city;
             $clientData['region'] = $faker->country; // You might need to adjust
             $clientData['postal_code'] = $faker->postcode;

            DB::table('clients')->insert($clientData);
        }

        // Generate 10 project records
        for ($i = 0; $i < 10; $i++) {
            $projectData = [
                'project_name' => $faker->sentence(4), // 4-word project name
                'start_date' => $faker->date('Y-m-d'),
                'end_date' => $faker->optional()->date('Y-m-d'), // Might be null
                'status' => $faker->randomElement(['ongoing', 'completed', 'paused']),
                'id_client' => rand(1, 10), // Assuming 10 clients exist (adjust if different)
                'user_id' => 2, // Replace with actual user ID
                'notes' => $faker->sentence(4),
                'require_deposit' => rand(0, 1), // Random boolean for deposit requirement
                'deposit_amount' => $faker->optional()->randomFloat(2, 0, 1000), // Might be null
                'deposit_percentage' => $faker->optional()->randomFloat(2, 0, 100), // Might be null
                'client_agrees_deposit' => rand(0, 1), // Random boolean for client agreement
                'invoice_type' => $faker->randomElement(['once', 'hourly', 'daily', 'weekly', 'monthly']),
            ];

            // Insert project record
            $projectId = DB::table('project_models')->insertGetId($projectData);

            // Generate random number of services (1-3) per project
            $serviceCount = rand(1, 3);
            for ($j = 0; $j < $serviceCount; $j++) {
                $serviceData = [
                    'id_project' => $projectId,
                    // Omit these foreign keys as they might not be directly used
                     'id_contract' => 1,
                     'id_quotation' => 1,
                    'service_name' => $faker->sentence(3), // Shorter service name
                    'price' => $faker->randomNumber(5, true), // Random price
                    'pay_method' => $faker->randomElement(['hourly', 'fixed']),
                    'description' => $faker->paragraph,

                ];

                $serviceAja = [
                    'id_project' => $projectId,
                    // Omit these foreign keys as they might not be directly used
                    'id_contract' => 1,
                    'id_quotation' => 1,
                ];

                // Insert service record
                $serviceId = DB::table('services')->insertGetId($serviceAja);

                // Generate random number of service details (1-5) per service
                $detailCount = rand(1, 5);
                for ($k = 0; $k < $detailCount; $k++) {
                    $detailData = [
                        'id_service' => $serviceId,
                        'service_name' => $serviceData['service_name'], // Use service name from parent
                        'price' => $faker->randomNumber(4, true), // Random price for detail
                        'pay_method' => $serviceData['pay_method'], // Use pay method from parent
                        'description' => $faker->sentence,
                    ];

                    // Insert service detail record
                    DB::table('service_details')->insert($detailData);
                }
            }
        }

        // Generate 10 quotation records
        for ($i = 0; $i < 10; $i++) {
            $quotationData = [
                'quotation_name' => $faker->sentence(4), // 4-word quotation name
                'start_date' => $faker->date('Y-m-d'),
                'end_date' => $faker->optional()->date('Y-m-d'), // Might be null
                'status' => $faker->randomElement(['draft', 'sent', 'approved', 'rejected']),
                'snap_token' => $faker->uuid, // Placeholder for payment gateway token
                'quotation_pdf' => $faker->word . '.pdf', // Placeholder for quotation PDF filename
                'id_client' => rand(1, 10), // Assuming 10 clients exist (adjust if different)
                'id_user' => 1, // Replace with actual user ID
                'id_project' => rand(1, 10), // Assuming 10 projects exist (adjust if different)
                'require_deposit' => rand(0, 1), // Random boolean for deposit requirement
                'deposit_amount' => $faker->optional()->randomFloat(2, 0, 1000), // Might be null
                'deposit_percentage' => $faker->optional()->randomFloat(2, 0, 100), // Might be null
                'client_agrees_deposit' => rand(0, 1), // Random boolean for client agreement
                'invoice_type' => $faker->randomElement(['once', 'hourly', 'daily', 'weekly', 'monthly']),
            ];

            // Insert quotation record
            $quotationId = DB::table('quotations')->insertGetId($quotationData);

            // Generate random number of services (1-3) per quotation
            $serviceCount = rand(1, 3);
            for ($j = 0; $j < $serviceCount; $j++) {
                $serviceData = [
                    'id_quotation' => $quotationId,
                    // Omit these foreign keys as they might not be directly used
                    // 'id_contract' => null,
                    // 'id_project' => null,
                    'service_name' => $faker->sentence(3), // Shorter service name
                    'price' => $faker->randomNumber(5, true), // Random price
                    'pay_method' => $faker->randomElement(['hourly', 'fixed']),
                    'description' => $faker->paragraph,
                ];

                $serviceAja = [
                    'id_quotation' => $quotationId,
                    // Omit these foreign keys as they might not be directly used
                     'id_contract' => 1,
                     'id_project' => 1,
                ];



                // Insert service record
                $serviceId = DB::table('services')->insertGetId($serviceAja);

                // Generate random number of service details (1-5) per service
                $detailCount = rand(1, 5);
                for ($k = 0; $k < $detailCount; $k++) {
                    $detailData = [
                        'id_service' => $serviceId,
                        'service_name' => $serviceData['service_name'], // Use service name from parent
                        'price' => $faker->randomNumber(4, true), // Random price for detail
                        'pay_method' => $serviceData['pay_method'], // Use pay method from parent
                        'description' => $faker->sentence,
                    ];

                    // Insert service detail record
                    DB::table('service_details')->insert($detailData);
                }
            }
        }

        // Generate 10 transaction records
        for ($i = 0; $i < 10; $i++) {
            $transactionData = [
                'id_user' => 1, // User ID 1 (replace if needed)
                'id_project' => rand(1, 10), // Assuming 10 projects exist (adjust if different)
                // Set other foreign keys (id_invoice, id_payment) to null for this example
                'id_invoice' => null,
                'id_payment' => null,
                'created_date' => $faker->date('Y-m-d'),
                'is_income' => rand(0, 1), // Random boolean for income/expense
                'source' => $faker->word, // Placeholder for transaction source
                'description' => $faker->sentence, // Transaction description
                'category' => $faker->word, // Placeholder for transaction category
                'amount' => $faker->randomNumber(5, true), // Random transaction amount
            ];

            // Insert transaction record
            DB::table('transactions')->insert($transactionData);
        }

        // Generate 10 contract records
        for ($i = 0; $i < 10; $i++) {
            $contractData = [
                'contract_name' => $faker->sentence(4), // 4-word contract name
                'start_date' => $faker->date('Y-m-d'),
                'end_date' => $faker->optional()->date('Y-m-d'), // Might be null
                'status' => $faker->randomElement(['draft', 'sent', 'signed', 'rejected']),
                'snap_token' => $faker->uuid, // Placeholder for payment gateway token
                'contract_pdf' => $faker->word . '.pdf', // Placeholder for contract PDF filename
                'id_client' => rand(1, 10), // Assuming 10 clients exist (adjust if different)
                'id_project' => rand(1, 10), // Assuming 10 projects exist (adjust if different)
                'id_user' => 1, // Replace with actual user ID
                'require_deposit' => rand(0, 1), // Random boolean for deposit requirement
                'deposit_amount' => $faker->optional()->randomFloat(2, 0, 1000), // Might be null
                'deposit_percentage' => $faker->optional()->randomFloat(2, 0, 100), // Might be null
                'client_agrees_deposit' => rand(0, 1), // Random boolean for client agreement
                'invoice_type' => $faker->randomElement(['once', 'hourly', 'daily', 'weekly', 'monthly']),
            ];

            // Insert contract record
            $contractId = DB::table('contracts')->insertGetId($contractData);

            // Generate random number of services (1-3) per contract
            $serviceCount = rand(1, 3);
            for ($j = 0; $j < $serviceCount; $j++) {
                $serviceData = [
                    'id_contract' => $contractId,
                    // Omit these foreign keys as they might not be directly used
                    // 'id_quotation' => null,
                    // 'id_project' => null,
                    'service_name' => $faker->sentence(3), // Shorter service name
                    'price' => $faker->randomNumber(5, true), // Random price
                    'pay_method' => $faker->randomElement(['hourly', 'fixed']),
                    'description' => $faker->paragraph,
                ];

                $serviceAja = [
                    'id_contract' => $contractId,
                    // Omit these foreign keys as they might not be directly used
                     'id_quotation' => 1,
                     'id_project' => 1,
                ];

                // Insert service record
                $serviceId = DB::table('services')->insertGetId($serviceAja);

                // Generate random number of service details (1-5) per service
                $detailCount = rand(1, 5);
                for ($k = 0; $k < $detailCount; $k++) {
                    $detailData = [
                        'id_service' => $serviceId,
                        'service_name' => $serviceData['service_name'], // Use service name from parent
                        'price' => $faker->randomNumber(4, true), // Random price for detail
                        'pay_method' => $serviceData['pay_method'], // Use pay method from parent
                        'description' => $faker->sentence,
                    ];

                    // Insert service detail record
                    DB::table('service_details')->insert($detailData);
                }
            }
        }

        // Generate 10 invoice records
        for ($i = 0; $i < 10; $i++) {
            // Randomly select project ID (assuming some projects exist)
            $projectId = rand(1, 10);

            // Get project details (optional)
            // You can uncomment this block and modify it to fetch actual project data
            // $projectData = getProjectDetails($projectId);
            // $clientId = $projectData['client_id']; // Assuming client ID is in project data

            // Use a fixed client ID for simplicity (adjust if needed)
            $clientId = 1;

            $invoiceData = [
                'id_project' => $projectId,
                'id_client' => $clientId,
                'issued_date' => $faker->date('Y-m-d'),
                'status' => $faker->randomElement(['draft', 'sent', 'paid']),
                'due_date' => $faker->optional()->date('Y-m-d'), // Might be null
                'total' => $faker->randomNumber(5, true), // Random invoice total
                'invoice_pdf' => $faker->word . '.pdf', // Placeholder for invoice PDF filename
                'user_id' => 2, // Replace with actual user ID if needed
            ];

            // Insert invoice record
            DB::table('invoices')->insert($invoiceData);
        }


    }
}
