<template>
    <DropdownMenu v-model:open="isOpen">
        <DropdownMenuTrigger as-child>
            <UiButton
                id="wallet-switch"
                size="sm"
                class="text-white border border-[#2F2F2F] bg-[#1E1E1E] hover:bg-[#313131] !rounded-md"
            >
                <span>selecionar carteira</span>
                <ChevronsUpDown v-if="!isOpen" class="ml-2 !h-4 !w-4 text-[#767676]" />
                <ChevronsDownUp v-else class="ml-2 !h-4 !w-4 text-[#767676]" />
            </UiButton>
        </DropdownMenuTrigger>
        <DropdownMenuContent class="w-56 bg-[#1E1E1E] border-[#2F2F2F] !rounded-md">
            <DropdownMenuLabel>my wallets</DropdownMenuLabel>
            <DropdownMenuSeparator class="bg-[#2F2F2F]"/>
            <DropdownMenuGroup class="max-h-[100px] overflow-y-auto">
                <DropdownMenuItem v-for="wallet in wallets" :key="wallet.id" class="!rounded-md cursor-pointer pl-2 hover:!bg-[#38353C] text-[#b8b8b8]">
                    <div class="flex items-center gap-x-0.5 justify-center">
                        <span class="text-sm font-medium text-[#6a64f2]">@</span>
                        <span class="text-sm">{{ wallet.name }}</span>
                    </div>
                </DropdownMenuItem>
            </DropdownMenuGroup>
            <DropdownMenuSeparator class="bg-[#2F2F2F]"/>
            <DropdownMenuGroup>
                <DropdownMenuItem class="!rounded-md cursor-pointer hover:!bg-[#38353C] text-[#b8b8b8]">
                    <Plus class="!h-4 !w-4" />
                    add wallet
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
            wallets: [
                {
                    id: 1,
                    name: 'roupas',
                    icon: 'https://github.com/shadcn.png'
                },
                {
                    id: 2,
                    name: 'ifood',
                    icon: 'https://github.com/shadcn.png'
                },
                {
                    id: 3,
                    name: 'mercado livre',
                    icon: 'https://github.com/shadcn.png'
                },
                {
                    id: 3,
                    name: 'pix',
                    icon: 'https://github.com/shadcn.png'
                },
                {
                    id: 3,
                    name: 'pix inter',
                    icon: 'https://github.com/shadcn.png'
                }

            ],
            isOpen: false
        }
    },
}

</script>
