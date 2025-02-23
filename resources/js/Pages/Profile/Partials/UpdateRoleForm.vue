<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const confirmingRoleUpdate = ref(false);
const props = defineProps({
    user: Object,
    availableRoles: Array
});

const selectedRoles = ref(new Set(props.user.roles));

const form = useForm({
    roles: props.user.roles
});

const toggleRole = (role) => {
    if (selectedRoles.value.has(role)) {
        selectedRoles.value.delete(role);
    } else {
        selectedRoles.value.add(role);
    }
    form.roles = Array.from(selectedRoles.value);
};

const confirmRoleUpdate = () => {
    confirmingRoleUpdate.value = true;
};

const updateRole = () => {
    form.put(route('roles.update', props.user.id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingRoleUpdate.value = false;
    selectedRoles.value = new Set(props.user.roles);
    form.reset();
};
</script>

<template>
    <ActionSection>
        <template #title>
            Update Roles
        </template>

        <template #description>
            Manage your account roles and permissions.
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                Your current {{ selectedRoles.size > 1 ? 'roles are' : 'role is' }}
                <span class="font-semibold">
                    {{ Array.from(selectedRoles).join(', ') }}
                </span>
            </div>

            <div class="mt-5 space-y-4">
                <div v-for="role in availableRoles" :key="role" class="flex items-center">
                    <input
                        type="checkbox"
                        :id="role"
                        :value="role"
                        :checked="selectedRoles.has(role)"
                        @change="toggleRole(role)"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    >
                    <label :for="role" class="ml-2 text-sm text-gray-600">
                        {{ role.charAt(0).toUpperCase() + role.slice(1) }}
                    </label>
                </div>

                <div class="mt-5">
                    <PrimaryButton
                        @click="confirmRoleUpdate"
                        :disabled="selectedRoles.size === 0"
                    >
                        Update Roles
                    </PrimaryButton>
                </div>
            </div>

            <!-- Update Role Confirmation Modal -->
            <DialogModal :show="confirmingRoleUpdate" @close="closeModal">
                <template #title>
                    Confirm Role Update
                </template>

                <template #content>
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">
                            Are you sure you want to update your roles to:
                            <span class="font-semibold">{{ Array.from(selectedRoles).join(', ') }}</span>?
                        </p>
                        <InputError :message="form.errors.roles" class="mt-2" />
                    </div>
                </template>

                <template #footer>
                    <SecondaryButton @click="closeModal">
                        Cancel
                    </SecondaryButton>

                    <PrimaryButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing || selectedRoles.size === 0"
                        @click="updateRole"
                    >
                        Confirm
                    </PrimaryButton>
                </template>
            </DialogModal>
        </template>
    </ActionSection>
</template>
