<script setup>
    import MagnifyingGlass from '@/Components/Icons/MagnifyingGlass.vue';
    import Pencil from '@/Components/Icons/Pencil.vue';
    import Trash from '@/Components/Icons/Trash.vue';
    import Pagination from '@/Components/Pagination.vue';
    import { Link, Head, useForm, router, usePage } from '@inertiajs/vue3'
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import axios from 'axios';
    import { ref, computed, watch } from 'vue';

    const props = defineProps({
        classes: {
            type: Object,
            required: true
        }
    })

    const flash = computed(() => usePage().props.flash);

    watch(flash, (newFlash) => {
        if (newFlash.success) {
            alert(newFlash.success);
        }
        if (newFlash.error) {
            alert(newFlash.error);
        }
    }, { deep: true })

    let search = ref(usePage().props.search), pageNumber = ref(1);

    let searchUrl = computed(() => {
        let url = new URL(route('classes.index'));
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

    const deleteClass = (classId) => {
        if (confirm('Are you sure you want to delete this class?')) {
            deleteForm.delete(route('classes.destroy', classId), {
                preserveScroll: true,
            });
        }
    }
</script>

<template>
    <Head title="Classes List" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Classes List
            </h2>
        </template>
        <div class="py-10">
            <div class="mx-auto max-w-7xl">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h1 class="text-xl font-regular text-white">
                                A list of all the Classes.
                            </h1>
                        </div>

                        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                            <Link
                                :href="route('classes.create')"
                                class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
                            >
                                Add Class
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
                                placeholder="Search classes data..."
                                id="search"
                                class="block rounded-lg dark:bg-gray-800 border-0 py-2 pl-10 text-gray-900 ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
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
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-white"
                                                >
                                                    Created At
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="relative py-3.5 pl-3 pr-4 sm:pr-6"
                                                />
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="divide-y divide-gray-200 bg-white"
                                        >
                                            <tr v-for="item in classes.data" :key="item.id" class="dark:bg-gray-700">
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-6"
                                                >
                                                    {{ item.id }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-6"
                                                >
                                                    {{ item.name }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-white"
                                                >
                                                    {{ item.created_at }}
                                                </td>

                                                <td class="flex pt-3">
                                                    <Link
                                                        :href="route('classes.edit', item.id)"
                                                        class="text-yellow-600 hover:text-yellow-800"
                                                    > 
                                                        <Pencil />
                                                    </Link>
                                                    <button
                                                        @click="deleteClass(item.id)"
                                                        class="ml-2 text-red-700 hover:text-red-900"
                                                    >
                                                        <Trash />
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <Pagination :meta="classes.meta" :updatedPageNumber="updatedPageNumber"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>