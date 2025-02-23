<script setup>
import {capitalize, ref, computed} from 'vue'
import {useForm} from '@inertiajs/vue3'
import PrimaryButton from "@/Components/PrimaryButton.vue"
import Checkbox from "@/Components/Checkbox.vue"
import FormSection from "@/Components/FormSection.vue"

const props = defineProps({
    roles: {type: Array, required: true},
    permissions: {type: Array, required: true}
})
const selectedRole = ref(props.roles[0]) // First serve basis
// Group all permissions and mark which ones are enabled for the selected role
const groupedPermissions = computed(() => {
    const permissions = {}
    // Group all available permissions and set them as false by default
    props.permissions.forEach(perm => {
        const [action, type] = perm.name.split(' ')
        if (!permissions[type]) {
            permissions[type] = {}
        }
        permissions[type][action] = false
    })
    // Set the ones that role has to true
    selectedRole.value.permissions.forEach(perm => {
        const [action, type] = perm.name.split(' ')
        if (permissions[type]) {
            permissions[type][action] = true
        }
    })
    return permissions
})

const form = useForm({
    permissions: groupedPermissions.value
})

// Update form when role changes
const updateSelectedRole = (role) => {
    selectedRole.value = role
    form.permissions = groupedPermissions.value
}

const updatePermissions = () => {
    form.post(route('permissions.update', selectedRole.value.id), {
        preserveScroll: true,
    })
}
</script>

<template>
    <div class="flex flex-col md:flex-row">
        <!-- Roles Left Aside -->
        <div class="md:w-1/4 p-6">
            <div class="mb-4">
                <h3 class="text-lg font-medium text-gray-900">Roles</h3>
            </div>
            <div class="space-y-2">
                <div
                    v-for="role in roles"
                    :key="role.id"
                    @click="updateSelectedRole(role)"
                    class="p-3 rounded-lg cursor-pointer"
                    :class="{
                    'bg-white border border-gray-200': selectedRole?.id === role.id,
                    'hover:bg-gray-50 border border-transparent': selectedRole?.id !== role.id
                }"
                >
                    <div class="flex justify-between items-center">
                        <span class="font-medium">{{ capitalize(role.name) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Permissions Section -->
        <div class="md:w-3/4 p-6">
            <FormSection
                class="!block md:!block"
                v-if="selectedRole"
                @submitted="updatePermissions"
            >
                <template #title>
                    Permissions for {{ capitalize(selectedRole.name) }}
                </template>
                <template #form>
                    <div v-for="(actions, type) in form.permissions"
                         :key="type"
                         class="col-span-6 mb-6">
                        <h4 class="font-medium text-gray-900 mb-4">{{ capitalize(type) }}</h4>
                        <div class="space-y-2">
                            <div v-for="(value, action) in actions"
                                 :key="action"
                                 class="flex items-center">
                                <label class="inline-flex items-center">
                                    <Checkbox
                                        :value="action"
                                        v-model:checked="form.permissions[type][action]"
                                    />
                                    <span class="ml-2">{{ capitalize(action) }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </template>
                <template #actions>
                    <PrimaryButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Save Changes
                    </PrimaryButton>
                </template>
            </FormSection>
        </div>
    </div>
</template>
