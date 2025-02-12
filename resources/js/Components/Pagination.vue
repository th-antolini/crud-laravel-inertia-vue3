<script setup>
    import { usePage } from '@inertiajs/vue3'
    import { router } from '@inertiajs/vue3'

    const props = defineProps({
        meta: {
            type: Object,
            required: true
        },
        updatedPageNumber: {
            type: Function,
            required: true
        }
    })
    
</script>

<template>
    <div class="max-w-7xl mx-auto py-6">
        <div class="max-w-none mx-auto">
            <div class="dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div
                    class="dark:bg-gray-800 px-4 py-3 flex items-center justify-between border-t border-gray-800 sm:px-6"
                >
                    <div class="flex-1 flex justify-between sm:hidden" />
                    <div
                        class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between"
                    >
                        <div>
                            <p class="text-sm text-gray-200">
                                Showing
                                <!-- space -->
                                <span class="font-medium">{{ meta.from }}</span>
                                <!-- space -->
                                to
                                <!-- space -->
                                <span class="font-medium">{{ meta.to }}</span>
                                <!-- space -->
                                of
                                <!-- space -->
                                <span class="font-medium"> {{ meta.total }} </span>
                                <!-- space -->
                                results
                            </p>
                        </div>
                        <div>
                            <nav
                                class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                aria-label="Pagination"
                            >
                                <button
                                    v-for="(link, index) in meta.links"
                                    :key="index"
                                    :disabled="link.active || !link.url"
                                    @click.prevent="updatedPageNumber(link)"
                                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium dark:bg-gray-800 border-gray-900"
                                    :class="{
                                        'z-10 bg-indigo-50 border-white text-white': link.active,
                                        'bg-white border-gray-300 text-gray-500 hover:bg-gray-900': !link.active,
                                    }"
                                >
                                    <span v-html="link.label"></span>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>