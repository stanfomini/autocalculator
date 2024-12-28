<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scheduler at /yestest</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 p-4" x-data="schedulerApp()">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Schedule an Appointment (YesTest)</h1>

        <div class="flex gap-3 mb-4">
            <button class="px-4 py-2 bg-blue-500 text-white rounded"
                    :class="{ 'bg-blue-700': tab === 'form' }"
                    @click="tab = 'form'">
                Book Now
            </button>
            <button class="px-4 py-2 bg-blue-500 text-white rounded"
                    :class="{ 'bg-blue-700': tab === 'list' }"
                    @click="tab = 'list'">
                View Appointments
            </button>
        </div>

        <!-- Form Tab -->
        <div x-show="tab === 'form'" class="space-y-4">
            <form @submit.prevent="createSchedule" class="space-y-3">
                <div>
                    <label class="block font-semibold">First Name</label>
                    <input type="text" x-model="form.first_name"
                           class="border p-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold">Last Name</label>
                    <input type="text" x-model="form.last_name"
                           class="border p-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold">Phone</label>
                    <input type="text" x-model="form.phone"
                           class="border p-2 w-full" required>
                </div>
                <div>
                    <label class="block font-semibold">Date & Time</label>
                    <input type="datetime-local" x-model="form.scheduled_at"
                           class="border p-2 w-full" required>
                </div>
                <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded">
                    Book Now
                </button>
            </form>
            <template x-if="message">
                <p class="text-green-600 font-semibold" x-text="message"></p>
            </template>
        </div>

        <!-- List Tab -->
        <div x-show="tab === 'list'">
            <h2 class="text-xl font-bold mb-3">Existing Appointments</h2>
            <ul class="space-y-2">
                <template x-for="item in schedules" :key="item.id">
                    <li class="p-3 bg-gray-50 rounded flex justify-between items-center">
                        <div>
                            <span class="font-semibold"
                                  x-text="item.first_name + ' ' + item.last_name"></span><br>
                            <span class="text-sm text-gray-600"
                                  x-text="item.phone"></span><br>
                            <span class="text-sm font-medium"
                                  x-text="formatDate(item.scheduled_at)"></span><br>
                            <small x-text="formatDate(item.created_at)"></small>
                        </div>
                        <div class="flex items-center gap-3">
                            <template x-if="item.is_new">
                                <span class="inline-block w-3 h-3 rounded-full bg-green-500"></span>
                            </template>
                            <button class="text-blue-600 text-sm underline"
                                    @click="editSchedule(item)">
                                Edit
                            </button>
                            <button class="text-red-600 text-sm underline"
                                    @click="deleteSchedule(item.id)">
                                Delete
                            </button>
                        </div>
                    </li>
                </template>
            </ul>

            <!-- Edit Modal -->
            <template x-if="editing">
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="bg-white p-4 rounded w-96 space-y-3">
                        <h3 class="text-lg font-bold">Edit Appointment</h3>
                        <form @submit.prevent="updateSchedule" class="space-y-3">
                            <div>
                                <label>First Name</label>
                                <input type="text" x-model="editForm.first_name"
                                       class="border p-2 w-full" required>
                            </div>
                            <div>
                                <label>Last Name</label>
                                <input type="text" x-model="editForm.last_name"
                                       class="border p-2 w-full" required>
                            </div>
                            <div>
                                <label>Phone</label>
                                <input type="text" x-model="editForm.phone"
                                       class="border p-2 w-full" required>
                            </div>
                            <div>
                                <label>Date & Time</label>
                                <input type="datetime-local" x-model="editForm.scheduled_at"
                                       class="border p-2 w-full" required>
                            </div>
                            <div class="flex justify-end gap-2">
                                <button type="button" @click="closeEdit"
                                        class="px-4 py-2 bg-gray-500 text-white rounded">
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
    function schedulerApp() {
        return {
            tab: 'form',
            message: '',
            schedules: [],
            form: {
                first_name: '',
                last_name: '',
                phone: '',
                scheduled_at: '',
            },
            editing: false,
            editId: null,
            editForm: {
                first_name: '',
                last_name: '',
                phone: '',
                scheduled_at: '',
            },
            async createSchedule() {
                this.message = '';
                const resp = await fetch('/yestest', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(this.form),
                });
                const json = await resp.json();
                if (json.status === 'success') {
                    this.message = 'Successfully booked!';
                    this.form = { first_name: '', last_name: '', phone: '', scheduled_at: '' };
                    this.tab = 'list';
                    this.fetchSchedules();
                } else {
                    alert('Failed to book appointment.');
                    console.error(json);
                }
            },
            async fetchSchedules() {
                // We can load SSE in init(), but let's also do a quick fetch for immediate data
                let resp = await fetch('/yestest/sse');
                // SSE read logic can be tricky with fetch, but let's at least try
                // For simpler immediate loads, consider a GET route returning JSON instead of SSE.
            },
            editSchedule(item) {
                this.editing = true;
                this.editId = item.id;
                this.editForm = {
                    first_name: item.first_name,
                    last_name: item.last_name,
                    phone: item.phone,
                    scheduled_at: item.scheduled_at.slice(0, 16),
                };
            },
            closeEdit() {
                this.editing = false;
                this.editId = null;
            },
            async updateSchedule() {
                const url = `/yestest/${this.editId}`;
                const resp = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(this.editForm),
                });
                const data = await resp.json();
                if (data.status === 'success') {
                    this.editing = false;
                    this.editId = null;
                } else {
                    alert('Update failed.');
                    console.error(data);
                }
            },
            async deleteSchedule(id) {
                if (!confirm('Are you sure?')) return;
                const url = `/yestest/${id}`;
                const resp = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
                const data = await resp.json();
                if (data.status !== 'deleted') {
                    alert('Delete failed.');
                    console.error(data);
                }
            },
            formatDate(dtStr) {
                let d = new Date(dtStr);
                return isNaN(d) ? dtStr : d.toLocaleString();
            },
            init() {
                // SSE
                const source = new EventSource('/yestest/sse');
                source.onmessage = (e) => {
                    try {
                        this.schedules = JSON.parse(e.data);
                    } catch (err) {
                        console.error('SSE parse error:', err);
                    }
                };
                source.onerror = (err) => {
                    console.error('SSE error:', err);
                };
            },
        };
    }
    </script>
</body>
</html>