<template>
    <FilterContainer>
        <span>{{ filter.name }}</span>
        <template #filter>
            <div class="relative">
                <Combobox v-model="selectedValue" multiple>
                    <div class="combobox-input-container" @click="onClick">
                        <div class="selected-option-button-list" ref="selectedOptionListEl">
                            <button
                                v-for="selectedOption in selectedOptions"
                                :aria-label="'remove ' + selectedOption.label"
                                class="selected-option-button"
                                @click="remove(selectedOption.value)"
                            >
                                <span>{{ selectedOption.label }}</span>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                </span>
                            </button>
                        </div>
                        <ComboboxInput @input="query = $event.target.value" class="combobox-input"
                                       :class="{'combobox-input--has-selected-options': selectedOptions.length > 0}"/>
                        <ComboboxButton class="combobox-button">
                            <svg class="flex-shrink-0 pointer-events-none form-select-arrow" xmlns="http://www.w3.org/2000/svg" width="10" height="6" viewBox="0 0 10 6"><path class="fill-current" d="M8.292893.292893c.390525-.390524 1.023689-.390524 1.414214 0 .390524.390525.390524 1.023689 0 1.414214l-4 4c-.390525.390524-1.023689.390524-1.414214 0l-4-4c-.390524-.390525-.390524-1.023689 0-1.414214.390525-.390524 1.023689-.390524 1.414214 0L5 3.585786 8.292893.292893z"></path></svg>
                        </ComboboxButton>
                    </div>
                    <ComboboxOptions class="combobox-options">
                        <ComboboxOption v-for="option in filteredOptions"
                            :key="option.value"
                            :value="option.value"
                            as="template"
                            v-slot="{active, selected}"
                        >
                            <li class="combobox-option" :class="{'combobox-option--is-active': active, 'combobox-option--is-selected': selected}">
                                <span class="combobox-option__label">{{ option.label }}</span>
                                <span class="combobox-option__icon" :class="{'combobox-option__icon--is-selected': selected}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="w-5 h-5"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                </span>
                            </li>
                        </ComboboxOption>
                    </ComboboxOptions>
                </Combobox>
            </div>
        </template>
    </FilterContainer>
</template>

<script setup>
import {ref, toRef, onMounted, onBeforeUnmount, computed, watch, inject} from 'vue';
import {Combobox, ComboboxInput, ComboboxOptions, ComboboxOption, ComboboxButton} from '@headlessui/vue'

let store = inject('store');

const emit = defineEmits([
    'change',
]);

const props = defineProps({
    resourceName: {
        type: String,
        required: true,
    },
    filterKey: {
        type: String,
        required: true,
    },
    lens: String,
});

const resourceName = toRef(props, 'resourceName');
const filterKey = toRef(props, 'filterKey');
const lens = toRef(props, 'lens');

let query = ref('');
let selectedValue = ref([]);
let selectedOptionListEl = ref(null);
let filter = computed(() => store.getters[`${resourceName.value}/getFilter`](filterKey.value));
let options = computed(() => filter.value.options);
let selectedOptions = computed(() => options.value.filter(option => selectedValue.value.includes(option.value)));
let filteredOptions = computed(() => query.value === '' ? options.value : options.value.filter(option => {
    return query.value === '' || option.label.toLowerCase().includes(query.value.toLowerCase());
}));

watch(selectedValue, function() {
    handleChange();
    query.value = '';
});

setCurrentFilterValue();

onMounted(function(){
    Nova.$on('filter-reset', setCurrentFilterValue);
});

onBeforeUnmount(function(){
    Nova.$off('filter-reset', setCurrentFilterValue);
});

function setCurrentFilterValue() {
    selectedValue.value = Array.from(filter.value.currentValue);
}

function handleChange() {
    store.commit(`${resourceName.value}/updateFilterState`, {
        filterClass: filterKey.value,
        value: Array.isArray(selectedValue.value) && selectedValue.value.length === 0 ? '' : selectedValue.value
    });

    emit('change');
}

function remove(optionValue) {
    const index = selectedValue.value.indexOf(optionValue);

    selectedValue.value.splice(index, 1);

    handleChange();
}

function onClick(e) {
    if (e.target.matches('button')) {
        return;
    }

    selectedOptionListEl.value.nextElementSibling.focus();
}
</script>

<style lang="scss" scoped>
.combobox-input-container {
    min-height: 2rem;
    border-radius: .25rem;
    position: relative;
    border-color: rgb(var(--colors-gray-300));
    border-width: 1px;
    overflow: hidden;

    .dark & {
        border-color: rgb(var(--colors-gray-700));
        --tw-ring-color: rgb(var(--colors-gray-700));
    }
    &:focus-within {
        border-color: rgba(var(--colors-primary-300));
        box-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color),
                    var(--tw-ring-inset) 0 0 0 calc(3px + var(--tw-ring-offset-width)) var(--tw-ring-color),
                    var(--tw-shadow, 0 0 transparent);
        outline: 2px solid transparent;
        outline-offset: 2px;

        .dark & {
            border-color: rgb(var(--colors-gray-400));
        }
    }

}

.combobox-input {
    width: 100%;
    padding-left: 1rem;
    padding-bottom: .5rem;
    padding-top: .5rem;
    height: 2rem;
    border: 0;
    font-size: .875rem;
    line-height: 1.25rem;
    color: rgba(var(--colors-gray-900));
    background-color: white;

    .dark & {
        background-color: rgb(var(--colors-gray-900));
        color: rgb(var(--colors-gray-400));
    }

    &:focus {
        outline: none;
    }

    &--has-selected-options {
        margin-top: 5px;
        border-top: 1px solid rgb(var(--colors-gray-300));

        .dark & {
            border-color: rgb(var(--colors-gray-700));
        }
    }
}

.combobox-button {
    display: flex;
    height: 100%;
    width: 22px;
    padding-bottom: 13px;
    position: absolute;
    top: 0;
    right: 0;
    align-items: flex-end;
    justify-content: center;
}

.selected-option-button-list {
    padding-left: 5px;
}

.selected-option-button {
    height: 22px;
    margin: 5px 5px 0 0;
    padding: 1px 25px 1px 5px;
    position: relative;
    background-color: rgba(var(--colors-primary-500));
    color: white;
    border-radius: 4px;

    & > :last-child {
        position: absolute;
        height: 100%;
        padding: 3px;
        aspect-ratio: 1/1;
        right: 0;
        top: 0;
        transition: .2s background-color;

        & > * {
            fill: rgba(black, .3);
        }
    }

    &:hover > :last-child {
        background-color: rgba(var(--colors-primary-600));

        & > * {
            fill: white;
        }
    }
}

.combobox-options {
    width: 100%;
    max-height: 200px;
    padding-bottom: .25rem;
    padding-top: .25rem;
    margin-top: .25rem;
    border-radius: .25rem;
    position: absolute;
    z-index: 1;
    overflow: auto;
    color: rgb(17, 24, 39);
    background-color: white;
    --combobox-options-boxshadow-base-color: 0, 0, 0;
    box-shadow: 0 10px 15px -3px rgba(var(--combobox-options-boxshadow-base-color), .1),
                0 4px 6px -2px rgba(var(--combobox-options-boxshadow-base-color), .05);

    .dark & {
        background-color: rgb(var(--colors-gray-900));
        color: rgb(var(--colors-gray-400));
        --combobox-options-boxshadow-base-color: 255, 255, 255;
    }
}

.combobox-option {
    padding: .5rem 1rem .5rem 2.5rem;
    font-size: 14px;
    line-height: 16px;
    cursor: pointer;
    position: relative;
    color: inherit;
    user-select: none;

    &--is-selected {
        font-weight: bold;
    }

    &--is-active,
    &:hover {
        background-color: rgba(var(--colors-primary-500));
        color: white;
    }

    &__label {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    &__icon {
        display: none;
        padding-left: .75rem;
        align-items: center;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;

        & > * {
            width: 1.25rem;
            aspect-ratio: 1/1;
        }

        &--is-selected {
            display: flex;

            & > * {
                fill: rgba(var(--colors-primary-500));
            }
        }
    }

    &:hover &__icon--is-selected > * {
        fill: currentColor;
    }
}
</style>
