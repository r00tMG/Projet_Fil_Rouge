<script>
import { onMounted, ref, computed } from "vue";
import axios from '@/axios.js'
import { useRoute } from "vue-router";
import Loader from "@/components/Loader.vue";
import logo from '@/assets/images/logo.png'
export default {
  name: 'Messagerie',
  components: {Loader},
  setup() {
    const users = ref([]);
    const searchQuery = ref('');
    const contenu = ref('');
    const route = useRoute();
    const messages = ref([])
    const discussion = ref([])
    const userId = ref(JSON.parse(localStorage.getItem('data')).user.id)
    const counts = ref('')
    const isLoading = ref(false);

    const filteredUsers = computed(() => {
      if (!searchQuery.value) return users.value;
      return users.value.filter(user =>
          user.name.toLowerCase().includes(searchQuery.value.toLowerCase())
      );
    });
    const getMessages = async () => {
      const response = await axios.get(`/messages/${route.params.id}`);
      messages.value = response.data.messages;
      //console.log('message de l\'utilisateur connecté',messages.value)

      const r = await axios.put(`/messages/mark-as-read/${userId.value}`, {}, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      });
      const rep = await r.data
      console.log(rep)

    };
    const fetchAllusers = async ()=>{
      const r = await axios.get('/getUsers', {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      });
      users.value = await r.data.users;
       //console.log('Liste des utilisateus',users.value)
    }

    const fetchUserSelected = async ()=>{
      const response = await axios.get(`/users/${route.params.id}`,{
        headers:{
          'Authorization':`Bearer ${localStorage.getItem('token')}`
        }
      })
      discussion.value = await response.data.user
      //console.log('l\'utilisateur selectionné',discussion.value)
    }
    const notifyUser = (message) => {
      if (Notification.permission === "granted") {
        new Notification("Nouveau message de " + message.emetteur.name, {
          body: message.contenu,
          icon: message.emetteur.storage + '/' + message.emetteur.photo_profile
        });
      } else if (Notification.permission !== "denied") {
        Notification.requestPermission().then(permission => {
          if (permission === "granted") {
            new Notification("Nouveau message de " + message.emetteur.name, {
              body: message.contenu,
              icon: message.emetteur.storage + '/' + message.emetteur.photo_profile
            });
          }
        });
      }
    };

    const fetchUnReadCountMessage = async () => {
      const r = await axios.get(`/messages/unread/${userId.value}`, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      });
      const unreadMessages = await r.data.unread_messages_by_discussion;

      const unreadCountByUser = {};
      unreadMessages.forEach((count) => {
        unreadCountByUser[count.emetteur_id] = count.unread_count;
      });
      counts.value = unreadCountByUser;

      console.log('Compteur des messages non lus par utilisateur', counts.value);
    };

    onMounted(async () => {

        await fetchAllusers()
        await fetchUnReadCountMessage()

        window.Echo.private(`chat.${route.params.id}`)
            .listen('MessageSent', (e) => {
              messages.value.push(e.message);
              notifyUser(e.message);
            })
            .listen('MessageRead', async () => {
              await fetchUnReadCountMessage();
            });

        if (Notification.permission !== "granted") {
          await Notification.requestPermission();
      }

    });

    const sendMessage = async () => {
      if (contenu.value.trim() === '') return;
      //console.log('l\'id de l\'utilisateur connecté',userId.value)

      try {
        const response = await axios.post('/messages', {
          contenu: contenu.value,
          emetteur_id: userId.value,
          recepteur_id: route.params.id,
        });
        const message = await response.data.message
        //console.log('message envoyé',message)
        messages.value.push(response.data.message);
        //console.log('Les messages envoyés',messages.value)

        contenu.value = '';
      } catch (error) {
        console.error('Erreur lors de l\'envoi du message:', error);
      }
    };
const submit = async (e) =>{
  //console.log(e.target)
  console.log(route.params.id)
  if(route.params.id)
    await fetchUserSelected()
  if(route.params.id)
    await getMessages()
}
    return {
      users,
      searchQuery,
      filteredUsers,
      contenu,
      sendMessage,
      discussion,
      messages,
      userId,
      submit,
      counts,
      route,
      isLoading,
      logo
    };
  }
}

</script>

<template>
  <Loader  :isLoading="isLoading"/>
  <div  class="messaging border border-success rounded-1">
    <div class="inbox_msg">
      <div class="inbox_people border border-success">
        <div class="headind_srch">
          <div class="recent_heading">
            <h4 class="text-success">Recent</h4>
          </div>
          <div class="srch_bar">
            <div class="stylish-input-group">
              <input type="text" class="search-bar" v-model="searchQuery" placeholder="Search">
              <span class="input-group-addon">
                <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
              </span>
            </div>
          </div>
        </div>
        <div class="inbox_chat">
          <router-link v-for="user in filteredUsers" :key="user.id" :to="`/messages/${user.id}/${user.name}`" :class="{ active_chat: $route.path === `/messages/${user.id}` }" @click="submit" class="">
          <div class="chat_list"  >
            <div class="chat_people">
              <div class="chat_img">
                <img :src="user.storage + '/' + user.photo_profile"  class="rounded-circle border border-success img-fluid" width="40" height="40">
              </div>
              <div class="chat_ib ">
                <h5 class="text-success">{{ user.name }}
                  <span class="chat_date badge badge-pill bg-danger" v-if="counts[user.id]">
                    {{ counts[user.id] }}
                  </span>
                </h5>
                <p>Message preview</p>
              </div>
            </div>
          </div>
          </router-link>
        </div>
      </div>
      <div class="mesgs">
        <Loader  :isLoading="isLoading"/>
        <div class="msg_history" v-if="route.params.id">
          <div v-if="messages" v-for="message in messages" :key="message.id">
            <div class="incoming_msg" v-if="message.recepteur.id === userId" >
              <div class="incoming_msg_img" >
                <img :src="discussion.storage + '/'+ discussion.photo_profile" class="rounded-circle border border-success img-fluid" width="40" height="40">
              </div>
              <div class="received_msg" >
                <h5 class="text-success">{{discussion.name}}</h5>
                <div class="received_withd_msg">
                  <p>{{message.contenu}}</p>
                  <span class="time_date"> 11:01 AM {{new Date(message.date_envoi).toLocaleString()}}    |    June 9</span></div>
              </div>
            </div>
            <div class="outgoing_msg" v-if="message.emetteur.id === userId">
              <div class="sent_msg" >
                <p class="bg-success">{{message.contenu}}</p>
                <span class="time_date"> 11:01 {{new Date(message.date_envoi).toLocaleString()}} AM    |    June 9</span>
              </div>
           </div>
          </div>
        </div>
        <div class="msg_history" v-else>
          <div class="container m-auto text-center mt-5">
            <img :src="logo" class="img-fluid logo " alt="logo" width="100px" height="100px">
            <p class="text-success">Votre système de messagerie</p>
          </div>
        </div>
        <div class="type_msg" v-if="route.params.id">
          <div class="input_msg_write">
            <input type="text" class="write_msg" v-model="contenu" placeholder="Type a message" />
            <button class="msg_send_btn  btn btn-sm btn-success"  @click.prevent="sendMessage" type="submit">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>




<style scoped>
.icon-count{
  background-color: #ff0000;
  color: #fff;
  font-size: 11px;
  padding: 2px;
  border-radius: 100px;
  width: 20px;
  height: 20px;

}


.container{max-width:1170px; margin:auto;}
img{ max-width:100%;}
.inbox_people {
  background: #f8f8f8 none repeat scroll 0 0;
  float: left;
  overflow: hidden;
  width: 40%; border-right:1px solid #c4c4c4;
}
.logo{
  width: 100px !important;
  height: 100px !important;
}
img {
  max-width: 100%;
  object-fit: cover; /* Ajuste l'image pour qu'elle rentre dans les dimensions tout en conservant le ratio */
  border-radius: 50%;
  width: 40px;
  height: 40px;
}
.inbox_msg {

  clear: both;
  overflow: hidden;
}
.top_spac{ margin: 20px 0 0;}


.recent_heading {float: left; width:40%;}
.srch_bar {
  display: inline-block;
  text-align: right;
  width: 60%;
}
.headind_srch{ padding:10px 29px 10px 20px; overflow:hidden; border-bottom:1px solid #c4c4c4;}

.recent_heading h4 {
  color: #05728f;
  font-size: 21px;
  margin: auto;
}
.srch_bar input{ border:1px solid #cdcdcd; border-width:0 0 1px 0; width:80%; padding:2px 0 4px 6px; background:none;}
.srch_bar .input-group-addon button {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  padding: 0;
  color: #707070;
  font-size: 18px;
}
.srch_bar .input-group-addon { margin: 0 0 0 -27px;}

.chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
.chat_ib h5 span{ font-size:13px; float:right;}
.chat_ib p{ font-size:14px; color:#989898; margin:auto}
.chat_img {
  float: left;
  width: 11%;
}
.chat_ib {
  float: left;
  padding: 0 0 0 15px;
  width: 88%;
}

.chat_people{ overflow:hidden; clear:both;}
.chat_list {
  border-bottom: 1px solid #c4c4c4;
  margin: 0;
  padding: 18px 16px 10px;
}
.inbox_chat { height: 550px; overflow-y: scroll;}

.active_chat{ background:#337447;}

.incoming_msg_img {
  display: inline-block;
  width: 6%;
}
.received_msg {
  display: inline-block;
  padding: 0 0 0 10px;
  vertical-align: top;
  width: 92%;
}
.received_withd_msg p {
  background: #ebebeb none repeat scroll 0 0;
  border-radius: 3px;
  color: #646464;
  font-size: 14px;
  margin: 0;
  padding: 5px 10px 5px 12px;
  width: 100%;
}
.time_date {
  color: #747474;
  display: block;
  font-size: 12px;
  margin: 8px 0 0;
}
.received_withd_msg { width: 57%;}
.mesgs {
  float: left;
  padding: 30px 15px 0 25px;
  width: 60%;
}

.sent_msg p {
  background: #05728f none repeat scroll 0 0;
  border-radius: 3px;
  font-size: 14px;
  margin: 0; color:#fff;
  padding: 5px 10px 5px 12px;
  width:100%;
}
.outgoing_msg{ overflow:hidden; margin:26px 0 26px;}
.sent_msg {
  float: right;
  width: 46%;
}
.input_msg_write input {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  color: #4c4c4c;
  font-size: 15px;
  min-height: 48px;
  width: 100%;
}

.type_msg {border-top: 1px solid #c4c4c4;position: relative;}
.msg_send_btn {
  /*background: #05728f none repeat scroll 0 0;*/
  border: medium none;
  border-radius: 50%;
  color: #fff;
  cursor: pointer;
  font-size: 17px;
  height: 33px;
  position: absolute;
  right: 0;
  top: 11px;
  width: 33px;
}
.messaging {
  /*padding: 0 0 50px 0;*/
}
.msg_history {
  height: 516px;
  overflow-y: auto;
}
</style>