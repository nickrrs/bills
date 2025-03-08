<template>
    <DropdownMenu v-model:open="isOpen">
        <DropdownMenuTrigger as-child>
            <UiButton
                id="account-switch"
                size="sm"
                class="text-white border border-[#2F2F2F] bg-gradient-to-br from-[#1E1F22] via-black to-[#0D0D0D] hover:bg-gradient-to-br hover:from-[#1E1F22] hover:via-[#1E1F22] hover:to-[#1E1F22]"
            >
                <span>select account</span>
                <ChevronsUpDown v-if="!isOpen" class="ml-2 !h-4 !w-4 text-[#767676]" />
                <ChevronsDownUp v-else class="ml-2 !h-4 !w-4 text-[#767676]" />
            </UiButton>
        </DropdownMenuTrigger>
        <DropdownMenuContent class="w-56 bg-[#1E1E1E] border-[#2F2F2F]">
            <DropdownMenuLabel>my accounts</DropdownMenuLabel>
            <DropdownMenuSeparator class="bg-[#2F2F2F]"/>
            <DropdownMenuGroup>
                <DropdownMenuItem v-for="account in accounts" :key="account.id" class="cursor-pointer pl-2 hover:!bg-[#38353C]">
                   <span>{{ account.name }}</span>
                </DropdownMenuItem>
            </DropdownMenuGroup>
            <DropdownMenuSeparator class="bg-[#2F2F2F]"/>
            <DropdownMenuGroup>
                <DropdownMenuItem class="cursor-pointer hover:!bg-[#38353C]">
                    <Plus class="!mr-2 !h-4 !w-4" />
                    add account
                </DropdownMenuItem>
            </DropdownMenuGroup>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
<script lang="ts">
import { Button as UiButton } from '@/components/ui/button'
import { ChevronsUpDown, ChevronsDownUp, Plus } from 'lucide-vue-next'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuGroup,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'

export default {
    components: {
        UiButton,
        DropdownMenu,
        DropdownMenuContent,
        DropdownMenuItem,
        DropdownMenuLabel,
        DropdownMenuSeparator,
        DropdownMenuTrigger,
        DropdownMenuGroup,
        ChevronsUpDown,
        ChevronsDownUp,
        Plus
    },
    data() {
        return {
            accounts: [
                {
                    id: 1,
                    name: '@account 1',
                    icon: 'https://github.com/shadcn.png'
                },
                {
                    id: 2,
                    name: '@account 2',
                    icon: 'https://github.com/shadcn.png'
                },
                {
                    id: 3,
                    name: '@account 3',
                    icon: 'https://github.com/shadcn.png'
                },
            ],
            isOpen: false
        }
    },
}

</script>
