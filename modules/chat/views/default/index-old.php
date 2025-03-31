<?php

use app\modules\chat\helpers\App;
use app\modules\chat\helpers\Html;

$this->title = 'Community Board' . (($activeSpace) ? ': ' . $activeSpace->name: 's');
$this->params['breadcrumbs'][] = $this->title;
$this->params['wrapCard'] = false;
$this->registerJsFile(
    App::publishedUrl('/js/chat.js', Yii::getAlias('@app/modules/chat/assets/assetsfiles')), [
    'type' => 'module',
    'depends' => ['app\modules\chat\assets\ThemeAsset']
]);
?> 
<div data-token="<?= $token ?>" id="chat-module" class="space-index chat-module" v-cloak>
    <div class="row">
        <div class="col-md-8">
            <div class="card card-custom card-stretch">
                <div class="d-flex flex-row"> 
                    <div class="flex-row-auto offcanvas-mobile w-300px w-xxl-300px" id="kt_profile_aside">
                        <div class="">
                            <div class="card-body px-2 py-5">
                                <spaces-container 
                                    :spaces="spaces" 
                                    :active-space="activeSpace" 
                                    :users="users"
                                    @save-new-space="saveNewSpace" 
                                    @select-space="selectSpace"></spaces-container>
                            </div>
                        </div>
                    </div>
                    <div class="flex-row-fluid d-block">
                        <button class="btn btn-primary mb-2 font-weight-bold mr-4 d-inline-block d-lg-none" id="btn-spaces-sidemenu">
                            Open Spaces
                        </button>
                        <div class="message-main-container">
                            <div v-if="! isObjectEmpty(activeSpace)"> 
                                <div class="card card-custom message-container">
                                    <div class="card-header align-items-center px-4 py-3 messages-header">
                                        <message-container-header 
                                            :active-space="activeSpace" 
                                            :space-groups="spaceGroups" 
                                            :available-users="availableUsers"
                                            :current-user="currentUser"
                                            @save-active-space="saveActiveSpace"
                                            @remove-member="removeMember"
                                            @save-member="saveMember"
                                            @leave-space="leaveSpace"
                                            @save-new-space="saveNewSpace"
                                            ></message-container-header>
                                    </div>
                                    <div class="card-body messages-body overflow-auto" ref="conversationsContainer" @scroll="messageScroll">
                                        <space-message-list :current-user="currentUser" :space-messages="spaceMessages" :message-form-state="messageFormState"></space-message-list>
                                    </div>
                                    <div class="card-footer align-items-center">
                                        <div class="text-center" v-if="showScrollable">
                                            <button @click="scrollToBottom" class="btn btn-outline-primary font-weight-bold btn-sm btn-pill btn-scroller">
                                                Scroll to Bottom
                                            </button>
                                        </div>
                                        <div v-if="activeSpace.is_block" class="text-center">
                                            <p class="lead font-weight-bold text-danger">
                                                This space coversation was blocked by {{ activeSpace.blockByUserFullname }}
                                            </p>
                                        </div>
                                        <div v-else>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <attachment-button :message-form="messageForm" @send-message="saveMessageWithAttachhments"></attachment-button>
                                                </div>
                                                <textarea placeholder="Enter message here" v-model="messageForm.content" class="form-control" rows="1" @keydown.enter.exact.prevent="handleEnterMessage" @keydown.enter.shift.exact.prevent="messageForm.content += '\n'"></textarea>

                                                <div class="input-group-append">
                                                    <button @click="saveNewMessage()" type="submit" class="btn btn-primary btn-lg text-uppercase font-weight-bold">
                                                        Send
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="card card-custom">
                                <div class="card-header align-items-center justify-content-around px-4 py-3">
                                    <h5 class="text-muted">
                                        {{ appState.isLoading ? 'Loading': 'No space selected' }}
                                    </h5>
                                </div>
                                <div class="card-body overflow-auto">
                                    <div class="text-center">
                                        <h4 class="font-weight-bolder text-muted mb-4">
                                            {{ appState.isLoading ? 'Loading': 'No messages to show' }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div> 
        </div>
        <div class="col-md-4">
            <div class="card card-custom card-stretch">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label text-dark-75" style="text-transform: uppercase;">
                            Ambulance Request Form
                        </h3>
                        <div class="card-toolbar">
                            <a target="_blank" :href="chatModule.myRequestUrl" class="btn btn-primary btn-sm font-weight-bold">
                                My Request History
                            </a>
                        </div>
                    </div>
                    
                </div>
                <div class="card-body" ref="ambulanceRequestContainer">
                    <ambulance-request-form></ambulance-request-form>
                </div>
            </div>
        </div>
    </div>
</div>


