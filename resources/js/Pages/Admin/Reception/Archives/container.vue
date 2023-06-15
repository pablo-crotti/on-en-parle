<template>
    <app-layout title="On en parle | Réception (Archives)">
        <adminChatContainer v-if="idProgram" :room="idProgram" :user="userId" />
        <div class="archives-wrapper">
            <archiveList :no="idProgram" :id="10"></archiveList>
        </div>
    </app-layout>
</template>

<script>
/**
 * Component: AppLayout
 * Description: Layout component for the admin section.
 *
 * Component: ArchiveList
 * Description: A component for displaying the list of archived items.
 *
 * Component: AdminChatContainer
 * Description: A container component for managing admin chat functionality.
 */

import AppLayout from "@/Layouts/AppLayoutAdmin.vue";
import archiveList from "./archiveList.vue";
import adminChatContainer from "@/Pages/MyComponents/adminChatContainer.vue";

export default {
    components: {
        AppLayout,
        archiveList,
        adminChatContainer,
    },
    props: ["userId"],
    data: function () {
        return {
            idProgram: window.location.href.split("/").pop(),
        };
    },
};
</script>
