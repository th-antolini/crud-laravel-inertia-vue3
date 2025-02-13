<script setup>
    import MagnifyingGlass from '@/Components/Icons/MagnifyingGlass.vue';
    import Pencil from '@/Components/Icons/Pencil.vue';
    import Trash from '@/Components/Icons/Trash.vue';
    import Pagination from '@/Components/Pagination.vue';
    import { Link, Head, useForm, router, usePage } from '@inertiajs/vue3'
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { ref, computed, watch } from 'vue';

    const props = defineProps({
        users: {
            type: Object,
            required: true
        }
    })

    let search = ref(usePage().props.search), pageNumber = ref(1);

    let searchUrl = computed(() => {
        let url = new URL(route('users.index'));
        url.searchParams.append("page", pageNumber.value);

        if (search.value) {
            url.searchParams.append("search", search.value);
        }

        return url;
    })

    const updatedPageNumber = (link) => {
        pageNumber.value = link.url.split('=')[1];
    }

    watch(() => searchUrl.value, (updatedSearchUrl) => {
        router.visit(updatedSearchUrl, {
            preserveScroll: true,
            preserveState: true,
            replace: true
        })
    })

    watch(() => search.value, (updatedSearch) => {
        if (updatedSearch) {
            pageNumber.value = 1;
        }
    })

    const deleteForm = useForm();

    const deleteUser = (userId) => {
        if (confirm('Are you sure you want to delete this user?')) {
            deleteForm.delete(route('users.destroy', userId), {
                preserveScroll: true,
            });
        }
    }
</script>

<template>
    <Head title="Users List" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Users List
            </h2>
        </template>
        <div class="py-10">
            <div class="mx-auto max-w-7xl">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h1 class="text-xl font-regular text-white">
                                A list of all the Users.
                            </h1>
                        </div>

                        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                            <Link
                                :href="route('users.create')"
                                class="inline-flex items-center justify-center rounded-md border border-transparent bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
                            >
                                Add User
                            </Link>
                        </div>
                    </div>

                    <div class="flex flex-col justify-between sm:flex-row mt-6">
                        <div class="relative text-sm text-gray-800 col-span-3">
                            <div
                                class="absolute pl-2 left-0 top-0 bottom-0 flex items-center pointer-events-none text-gray-500"
                            >
                                <MagnifyingGlass />
                            </div>

                            <input
                                v-model="search"
                                type="text"
                                autocomplete="off"
                                placeholder="Search users data..."
                                id="search"
                                class="block dark:bg-gray-800 rounded-lg border-0 py-2 pl-10 text-white ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            />
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col">
                        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div
                                class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8"
                            >
                                <div
                                    class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg relative"
                                >
                                    <table
                                        class="min-w-full divide-y divide-gray-300"
                                    >
                                        <thead class="dark:bg-gray-800">
                                            <tr>
                                                <th
                                                    scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6"
                                                >
                                                    ID
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6"
                                                >
                                                    Name
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6"
                                                >
                                                    Email
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-white"
                                                >
                                                    Created At
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="relative py-3.5 pr-4 text-sm sm:pr-6 text-left text-white"
                                                >
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="divide-y divide-gray-200 bg-white"
                                        >
                                            <tr v-for="user in users.data" :key="user.id" class="dark:bg-gray-700">
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-6"
                                                >
                                                    {{ user.id }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-6"
                                                >
                                                    {{ user.name }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-white"
                                                >
                                                    {{ user.email }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-white"
                                                >
                                                    {{ user.created_at }}
                                                </td>

                                                <td class="flex pt-3">
                                                    <Link
                                                        :href="route('users.edit', user.id)"
                                                        class="text-yellow-600 hover:text-yellow-800"
                                                        title="Edit User"
                                                    > 
                                                        <Pencil />
                                                    </Link>
                                                    <button
                                                        @click="deleteUser(user.id)"
                                                        class="ml-2 text-red-700 hover:text-red-900"
                                                        title="Delete User"
                                                    >
                                                        <Trash />
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <Pagination :meta="users.meta" :updatedPageNumber="updatedPageNumber"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>