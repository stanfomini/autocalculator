<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking SPA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 p-4" x-data="bookingApp()">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Bookings (SPA)</h1>

        <div class="flex gap-4 mb-4">
            <button class="px-4 py-2 bg-blue-500 text-white rounded"
                    :class="{ 'bg-blue-700': tab === 'form' }"
                    @click="tab = 'form'">
                Book Now
            </button>
            <button class="px-4 py-2 bg-blue-500 text-white rounded"
                    :class="{ 'bg-blue-700': tab === 'list' }"
                    @click="tab = 'list'">
                View Bookings
            </button>
        </div>

        <!-- TAB 1: Booking form -->
        <div x-show="tab === 'form'" class="space-y-4">
            <form @submit.prevent="createBooking" class="space-y-4">
                <div>
                    <label class="block font-semibold">First Name</label>
                    <input type="text" x-model="form.first_name" class="border p-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold">Last Name</label>
                    <input type="text" x-model="form.last_name" class="border p-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold">Phone</label>
                    <input type="text" x-model="form.phone" class="border p-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold">Date & Time</label>
                    <input type="datetime-local" x-model="form.booking_datetime" class="border p-2 w-full" required>
                </div>
                <button class="px-4 py-2 bg-green-500 text-white rounded" type="submit">
                    Book Now
                </button>
            </form>
            <template x-if="message">
                <div class="text-green-600 font-semibold" x-text="message"></div>
            </template>
        </div>

        <!-- TAB 2: Booking list -->
        <div x-show="tab === 'list'">
            <h2 class="text-xl font-bold mb-2">Current Bookings</h2>
            <p class="text-gray-500 text-sm mb-2">Real-time updates (SSE) every 3s</p>
            <ul class="space-y-2">
                <template x-for="b in bookings" :key="b.id">
                    <li class="p-3 bg-gray-50 rounded flex items-center justify-between">
                        <div>
                            <span class="font-semibold" x-text="b.first_name + ' ' + b.last_name"></span><br>
                            <span class="text-sm text-gray-600" x-text="b.phone"></span><br>
                            <span class="text-sm font-medium" x-text="formatDate(b.booking_datetime)"></span>
                        </div>
                        <div class="flex gap-3 items-center">
                            <!-- Green circle if new -->
                            <template x-if="b.is_new">
                                <span class="inline-block w-3 h-3 rounded-full bg-green-500"></span>
                            </template>
                            <!-- Edit button -->
                            <button class="text-blue-600 text-sm underline"
                                    @click="editBooking(b)">
                                Edit
                            </button>
                            <!-- Delete button -->
                            <button class="text-red-600 text-sm underline"
                                    @click="deleteBooking(b.id)">
                                Delete
                            </button>
                        </div>
                    </li>
                </template>
            </ul>

            <!-- Edit modal (optional) -->
            <template x-if="editing">
                <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white p-6 rounded w-96 space-y-4">
                        <h3 class="text-lg font-bold">Edit Booking</h3>
                        <form @submit.prevent="updateBooking" class="space-y-3">
                            <div>
                                <label class="block font-semibold">First Name</label>
                                <input type="text" x-model="editForm.first_name" class="border p-2 w-full" required>
                            </div>
                            <div>
                                <label class="block font-semibold">Last Name</label>
                                <input type="text" x-model="editForm.last_name" class="border p-2 w-full" required>
                            </div>
                            <div>
                                <label class="block font-semibold">Phone</label>
                                <input type="text" x-model="editForm.phone" class="border p-2 w-full" required>
                            </div>
                            <div>
                                <label class="block font-semibold">Date & Time</label>
                                <input type="datetime-local" x-model="editForm.booking_datetime"
                                       class="border p-2 w-full" required>
                            </div>
                            <div class="flex justify-end gap-2">
                                <button type="button"
                                        class="px-4 py-2 bg-gray-500 text-white rounded"
                                        @click="closeEdit">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <script>
    function bookingApp() {
        return {
            tab: 'form',
            message: '',
            // Live data from SSE
            bookings: [],
            // Form for new booking
            form: {
                first_name: '',
                last_name: '',
                phone: '',
                booking_datetime: '',
            },
            // Editing state
            editing: false,
            editId: null,
            editForm: {
                first_name: '',
                last_name: '',
                phone: '',
                booking_datetime: '',
            },
            async createBooking() {
                this.message = '';
                // Post to /booking
                const resp = await fetch('/booking', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(this.form),
                });
                const data = await resp.json();
                if (data.status === 'success') {
                    this.message = 'Booking saved successfully!';
                    // Clear form
                    this.form = {
                        first_name: '',
                        last_name: '',
                        phone: '',
                        booking_datetime: '',
                    };
                    // Switch to list
                    this.tab = 'list';
                }
            },
            editBooking(booking) {
                this.editId = booking.id;
                this.editing = true;
                this.editForm = {
                    first_name: booking.first_name,
                    last_name: booking.last_name,
                    phone: booking.phone,
                    booking_datetime: booking.booking_datetime?.slice(0,16),
                };
            },
            closeEdit() {
                this.editing = false;
                this.editId = null;
            },
            async updateBooking() {
                const resp = await fetch('/booking/' + this.editId, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(this.editForm),
                });
                const data = await resp.json();
                if (data.status === 'success') {
                    this.editing = false;
                    this.editId = null;
                }
            },
            async deleteBooking(id) {
                if (!confirm('Are you sure you want to delete this booking?')) {
                    return;
                }
                const resp = await fetch('/booking/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
                const json = await resp.json();
                if (json.status === 'deleted') {
                    // SSE list will remove it on next refresh
                }
            },
            formatDate(dtStr) {
                const d = new Date(dtStr);
                return d.toLocaleString();
            },
            init() {
                // SSE endpoint
                const source = new EventSource('/booking/sse');
                source.onmessage = (event) => {
                    try {
                        this.bookings = JSON.parse(event.data);
                    } catch (err) {
                        console.error('SSE parse error', err);
                    }
                };
                source.onerror = (err) => {
                    console.error('SSE error', err);
                };
            },
        };
    }
    </script>
</body>
</html>