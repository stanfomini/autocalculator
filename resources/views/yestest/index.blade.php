<!DOCTYPE html>
<html lang="en" x-data="yesTestApp()" x-init="init()">
<head>
    <meta charset="UTF-8">
    <title>YesTest App at /yestest</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 p-4">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">YesTest Scheduling</h1>
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
            <form @submit.prevent="createRecord" class="space-y-3">
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
            <h2 class="text-xl font-bold mb-3">Current Appointments</h2>
            <ul class="space-y-2">
                <template x-for="item in items" :key="item.id">
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
                                    @click="editItem(item)">
                                Edit
                            </button>
                            <button class="text-red-600 text-sm underline"
                                    @click="deleteItem(item.id)">
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
                        <h3 class="text-lg font-bold">Edit</h3>
                        <form @submit.prevent="updateItem" class="space-y-3">
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
    function yesTestApp() {
        return {
            tab: 'form',
            message: '',
            items: [],
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
            async createRecord() {
                this.message = '';
                let resp = await fetch('/yestest', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").content
                    },
                    body: JSON.stringify(this.form)
                });
                let data = await resp.json();
                if (data.status === 'success') {
                    this.message = 'Appointment booked!';
                    // Clear the form
                    this.form = { first_name: '', last_name: '', phone: '', scheduled_at: '' };
                    // Switch to list tab so user can see it (SSE updates after ~3s)
                    this.tab = 'list';
                } else {
                    alert('Failed to book appointment. Check console for errors.');
                    console.error(data);
                }
            },
            editItem(item) {
                this.editing = true;
                this.editId = item.id;
                this.editForm = {
                    first_name: item.first_name,
                    last_name: item.last_name,
                    phone: item.phone,
                    scheduled_at: item.scheduled_at.slice(0,16),
                };
            },
            closeEdit() {
                this.editing = false;
                this.editId = null;
            },
            async updateItem() {
                const url = `/yestest/${this.editId}`;
                let resp = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").content
                    },
                    body: JSON.stringify(this.editForm)
                });
                let data = await resp.json();
                if (data.status === 'success') {
                    this.editing = false;
                    this.editId = null;
                } else {
                    alert('Update failed. Check console.');
                    console.error(data);
                }
            },
            async deleteItem(id) {
                if (!confirm('Are you sure?')) return;
                const url = `/yestest/${id}`;
                let resp = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").content
                    },
                });
                let data = await resp.json();
                if (data.status !== 'deleted') {
                    alert('Delete failed. Check console.');
                    console.error(data);
                }
            },
            formatDate(dtStr) {
                let d = new Date(dtStr);
                if (isNaN(d)) return dtStr;
                return d.toLocaleString();
            },
            init() {
                // SSE
                const source = new EventSource('/yestest/sse');
                source.onmessage = (e) => {
                    try {
                        this.items = JSON.parse(e.data);
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