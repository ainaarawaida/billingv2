<x-filament-panels::page>

<header class="bg-gray-800 text-white flex flex-col justify-center items-center px-4 py-8">
    <img class="m-5 bg-white w-24 rounded-lg self-center" src="{{ asset('image.psd.png') }}" alt="">
            
    <h1 class="text-3xl font-bold">Streamline Your Billing</h1>
    <p class="text-lg mt-2">Effortless management for your finances.</p>
    <p class="p-5 text-center">Introducing an online e-billing system specially developed for individual and small business use. The system is simply designed to make it easy for you to use to generate invoices, quotes, and even receive payments from customers.</p>

  </header>

  <div class="flex gap-2">
      <div class="w-full md:w-1/2 bg-white rounded-lg shadow-md p-8">
            <h3 class="text-xl font-bold mb-4">Register</h3>
            <p class="text-gray-700 mb-6">Free Online Billing System. Create and share invoices in 1-click. Collect faster payments with auto-reminders. Get insightful reports. Customize, Download</p>
            <a href="{{ url('app/register') }}" wire:navigate class="text-white bg-blue-500 hover:bg-blue-600 font-bold py-2 px-4 rounded-md shadow-sm inline-flex items-center">
              {{ __('Free Registration') }}
              <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
          </div>
    
          
    <div class="w-full md:w-1/2 bg-white rounded-lg shadow-md p-8">
        <h3 class="text-xl font-bold mb-4">Login</h3>
        <p class="text-gray-700 mb-6">Simplify your billing process with our user-friendly platform. Get a clear overview of your finances and manage invoices effortlessly.</p>
        <a href="{{ url('app/login') }}" wire:navigate class="text-white bg-blue-500 hover:bg-blue-600 font-bold py-2 px-4 rounded-md shadow-sm inline-flex items-center">
        {{ __('Login') }}
            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>

  </div>

  <footer class="bg-gray-800 text-white py-4 px-8 flex justify-between items-center">
  <p><a href="https://ainaarawaida.com">© {{ date('Y')}} - Developed by 4in44.com</a></p>
  <ul class="flex space-x-4">
    <li><a href="#">About Us</a></li>
    <li><a href="#">Contact</a></li>
    <li><a href="#">Terms</a></li>
  </ul>
</footer>

    </x-filament-panels::page>
