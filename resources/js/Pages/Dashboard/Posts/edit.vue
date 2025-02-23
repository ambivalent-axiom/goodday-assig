<script setup>
import {ref, onMounted, capitalize} from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import FormSection from '@/Components/FormSection.vue';
import ActionMessage from '@/Components/ActionMessage.vue';

const props = defineProps({
    post: {type: Object,
        default: () => ({
            title: '',
            description: '',
            text: '',
            image: null
        })
    },
    mode: {type: String, default: 'create', required: true,
        validator: (value) => ['create', 'edit'].includes(value)
    },
    type: {type: String, required: true,}
});

const form = useForm({
    title: props.post.title,
    description: props.post.description,
    text: props.post.text,
    image: null
});

const imagePreview = ref(props.post.image || null);
const recentlySuccessful = ref(false);

const selectNewImage = (e) => {
    const file = e.target.files[0];
    if (!file) return;

    form.image = file;
    const reader = new FileReader();
    reader.onload = (e) => {
        imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
};

const submit = () => {
    if (props.mode === 'create') {
        form.post(route(props.type + '.store'), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                imagePreview.value = null;
                recentlySuccessful.value = true;
                setTimeout(() => recentlySuccessful.value = false, 2000);
            }
        });
    } else {
        form.post(route(props.type + '.update', props.post.id), {
            preserveScroll: true,
            onSuccess: () => {
                recentlySuccessful.value = true;
                setTimeout(() => recentlySuccessful.value = false, 2000);
            }
        });
    }
};
</script>

<template>
    <FormSection
        @submitted="submit"
        class="!block md:!block bg-gray-50"
    >
        <template #title>
            <div class="flex justify-center items-center text-2xl font-semibold text-gray-800 m-4">
                {{ mode === 'create' ? 'Create New ' + capitalize(type) + ' Post' : 'Edit ' + capitalize(type) + ' Post' }}
            </div>
        </template>

        <template #description>
            <div class="text-sm text-gray-600 mt-1 text-center m-4">
                {{ mode === 'create'
                ? 'Create a new ' + type + ' post with a title, description, and content.'
                : 'Update your ' + type + ' post information.'
                }}
            </div>
        </template>


        <template #form>
            <!-- Title -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="title" value="Title" />
                <TextInput
                    id="title"
                    v-model="form.title"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="off"
                />
                <InputError :message="form.errors.title" class="mt-2" />
            </div>

            <!-- Description -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="description" value="Description" />
                <TextInput
                    id="description"
                    v-model="form.description"
                    class="mt-1 block w-full"
                    rows="3"
                />
                <InputError :message="form.errors.description" class="mt-2" />
            </div>

            <!-- Main Content -->
            <div class="col-span-6">
                <InputLabel for="text" value="Content" />
                <textarea
                    id="text"
                    v-model="form.text"
                    class="w-full mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    rows="8"
                    placeholder="Write your blog post content here..."
                ></textarea>
                <InputError :message="form.errors.text" class="mt-2" />
            </div>

            <!-- Image Upload -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="image" value="Featured Image" />

                <!-- Preview -->
                <div v-if="imagePreview" class="mt-2">
                    <img :src="imagePreview" class="rounded-lg max-h-48 object-cover" alt="Preview">
                </div>

                <SecondaryButton
                    class="mt-2"
                    type="button"
                    @click="$refs.image.click()"
                >
                    Select Image
                </SecondaryButton>

                <input
                    ref="image"
                    type="file"
                    class="hidden"
                    @change="selectNewImage"
                    accept="image/*"
                >

                <InputError :message="form.errors.image" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="recentlySuccessful" class="mr-3">
                Saved.
            </ActionMessage>

            <PrimaryButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                {{ mode === 'create' ? 'Create Post' : 'Update Post' }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
