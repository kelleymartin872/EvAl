<template>
    <!-- <router-link 
        style="text-decoration: none; color: inherit;" 
        :to="{ name: 'postPage', params: { id } }"
        class=""
    > -->
        <div class="cardParent" style="position: relative;">
            <v-card
                class="postMain"
                min-width="14.5rem"
                max-width="14.5rem"
                :elevation="elevation"
                @mouseover="elevation=2"
                @mouseleave="elevation=0"
                style="text-decoration: none; color: inherit;" 
                :to="{ name: 'postPage', params: { id } }"
            >

                <div class="postBadges d-flex rounded align-items-center">
                    <div v-if="isVip" class="d-flex" style="background-color: transparent;" title="VIP elan">
                        <v-icon
                            color="red"
                            size="25"
                        >
                            mdi-crown
                        </v-icon>
                    </div>
                    
                    <div v-if="hasVoucher" class="d-flex" style="background-color: transparent;" title="Çıxarış var">
                        <v-icon
                            color="orange"
                            size="25"
                        >
                            mdi-text-box-check
                        </v-icon>
                    </div>
                </div>

                <!-- max-height="32rem"
                min-height="32rem" -->
                <v-img
                    class="white--text align-end"
                    height="10.8rem"
                    :src="imageUrl"
                    style="background-size: contain;"
                >
                    <template v-slot:placeholder>
                        <v-skeleton-loader
                            :loading="true"
                            class="mx-auto"
                            type="card"
                            style=""
                        ></v-skeleton-loader>
                    </template>

                    <div v-if="realtorType=='agent' && agency!=null" class="postAgency rounded" title="Agentlik" @mouseover="elevation=2">
                        <!-- <v-chip
                            small
                            label 
                            color="primary"
                        >
                            Agency
                        </v-chip> -->
                    
                        <div style="background-color: transparent; margin: 2px;">
                            <v-icon
                                size="20"
                                color="white"
                            >
                                mdi-domain
                            </v-icon>
                        </div>

                    </div>
                </v-img>

                <v-card-title
                    style=""
                    class="postPrice pl-3"
                >
                    {{ formattedPrice }} AZN<span v-if="tradeType=='rent'">/ay</span>
                </v-card-title> <!--₼ () -->

                <div class="d-flex flex-column">
                    <div style="">
                        <v-card-subtitle class="p-0 pl-3 pb-2">
                            <!-- <v-chip
                                color="primary"
                                class="mb-2"
                                small
                            >
                                {{ type }}
                            </v-chip> -->
                        
                            <p class="districtName">{{ district }}</p>
                            <p class="postStats">
                                <span v-if="roomCount">{{ roomCount }} rooms</span>
                                <span v-if="area">
                                    <span v-if="roomCount">•</span>
                                    {{ area }} m²
                                </span>
                                <span v-if="apartmentFloor && totalFloors">
                                    <span v-if="area">•</span>
                                    {{ apartmentFloor }}/{{ totalFloors }} floor
                                </span>

                            </p>
                            <p class="postFooter">
                                {{ cityAndTime }}
                            </p>
                        </v-card-subtitle>
                        <!-- <div>
                            by {{ user.name }}
                        </div> -->
                    </div>
                    
                    <!-- <div style="">
                        <v-card-actions>
                            <v-btn
                                color="orange"
                                text
                            >
                                See more
                            </v-btn>
                        </v-card-actions>
                    </div> -->
                </div>
            </v-card>

            <div>
                    <div v-if="userIsAdmin">
                        <v-icon
                            class="postDelete"
                            color="red"
                            @click="handlePostDelete"
                        >
                            mdi-delete
                        </v-icon>
                    </div>
                <!-- Removing this fixes transition -->
                <div 
                    class="postLike"
                    @click="handleSave"
                    @mouseover="elevation=2"
                    v-if="isPostSaved"
                >
                    <div
                        :style="'text-fill-color: #77A43D;'"
                    >
                        <v-icon
                            class="bookmark_icon"
                            color="#00000090"
                            :title="'Yaddaşdan sil'"
                        >
                            mdi-bookmark
                        </v-icon>
                    </div>
                </div>

                <div 
                    class="postLike"
                    @click="handleSave"
                    @mouseover="elevation=2"
                    v-if="!isPostSaved"
                >
                    <div
                        :style="'text-fill-color: #00000050;'"
                    >
                        <v-icon
                            class="bookmark_icon"
                            color="#00000090"
                            :title="'Yadda saxla'"
                        >
                            mdi-bookmark
                        </v-icon>
                    </div>
                </div>
                
            </div>

        </div>
    <!-- </router-link> -->

    
</template>

<script>
import { mapState } from "vuex";
import { mapActions } from 'vuex';

export default {
    props: [
        "id",

        "user",
        "agency",
        "type",
        "city",
        "area",
        "roomCount",
        "address",
        "district",
        "apartmentFloor",
        "totalFloors",
        "price",
        "tradeType",
        "isVip",
        "hasVoucher",
        "realtorType",
        "createdAt",
        "loading"
    ],
    data() {
        return {
            elevation: 0,
            liked: false,

            postMedia: {},

            // localUser: {}
        }
    },
    methods: {
        ...mapActions(["savePost", "unsavePost"]),

        handleSave() {
            if(this.isPostSaved == false) {
                this.savePost(this.id)
            }
            else {
                this.unsavePost(this.id)
            }
        },

        // get post media from PostMediaController
        getPostMedia(postId) {
            axios.get(`/api/post/${postId}/media`)
                .then((res) => {
                    this.postMedia = JSON.parse(JSON.stringify(res.data));
                    console.log(this.postMedia)
                })
                .catch((err) => {
                    console.log(err);
                })
        },

        handlePostDelete() {
            axios.delete(`/api/post/${this.id}`, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem("sanctum_token")
                }
            })
            .then((res) => {
                console.log(res)

                this.$router.go()
            })
            .catch((err) => {
                console.log(err)
            })
        },

        // fetchUser() {
        //     axios.get(`/api/user`, {
        //         headers: {
        //             'Authorization': 'Bearer ' + localStorage.getItem("sanctum_token")
        //         }
        //     })
        //     .then((res) => {
        //         console.log(res.data)
        //         this.localUser = res.data
        //     })
        //     .catch((err) => {
        //         console.log(err)
        //     })
        // }
    },
    computed: {
        ...mapState(["savedPosts"]),

        isPostSaved() {
            return this.savedPosts.includes(this.id)
        },

        formattedPrice: function() {
            return this.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        },

        cityAndTime: function() {
            return `${this.city}, ${this.createdAt.slice(8, 10)}-${this.createdAt.slice(5,7)}-${this.createdAt.slice(0, 4)}`
        },

        imageUrl() {
            return this.postMedia.original_url ?? `https://picsum.photos/id/${this.id%200}/1920/1080`;
        },
        
        userIsAdmin() {
            return this.$store.state?.user?.role == 'administrator'
        },
    },
    mounted() {
        // console.log(this.agency)
        // console.log(this.realtorType)
        // console.log(this.agency)
        if(this.id < 100) {
            this.getPostMedia(this.id);
        }

        // this.fetchUser()
    },
}
</script>

<style scoped>
.cardParent {
    cursor: pointer;
    /* color: #f2f566 */
}

.postMain {
    border-radius: 7px !important; 
    overflow: hidden; /* fixes round border issue */
}

.postBadges {
    position: absolute;
    top: 7px;
    left: 7px;
    z-index: 1;

    background-color: rgba(255, 255, 255, 1);
}

.postLike {
    position: absolute;
    top: 7px;
    right: 7px;
    z-index: 1;
}

.postDelete {
    position: absolute;
    bottom: 25px;
    right: -85%;
    z-index: 1;

    background-color: rgba(255, 255, 255, 0.9);
    border-radius: 4px;
}

.bookmark-enter-active .bookmark-leave-active {
    transition: all 0.5s;
}

.bookmark-enter .bookmark-leave-to {
    transition: all 0.5s;
}

.postAgency {
    position: absolute;
    bottom: 7px;
    left: 7px;
    z-index: 1;

    background-color: rgb(0, 83, 192);
}

/* adds stroke to heart icon in post component */
.mdi-bookmark::before {
    /* -webkit-text-fill-color: #00000050; */
    -webkit-text-stroke-width: 1px;
    -webkit-text-stroke-color: white;
}

.postPrice {
    /* border-radius: 7px !important; */

    font-size: 1.15rem !important;
    font-weight: bold !important;
    letter-spacing: 0px !important;

    padding-top: 0px !important;
    padding-bottom: 0px !important;
    margin-top: 8px !important;
}

.districtName {
    font-size: 1rem;
    font-weight: 400 !important;
    letter-spacing: 0px !important;
    line-height: 1rem;
    font-weight: 500;
    letter-spacing: 0.1px;
}

.postStats {
    font-size: 14px;
    font-weight: 400 !important;
    letter-spacing: 0px !important;
    line-height: 17px;

    margin-top: 1px;
    margin-bottom: 1px;
}

.postFooter {
    font-size: 14px;
    line-height: 17px;

    letter-spacing: 0px !important;
    color: #969595;
}

/* bookmark animation */
.myTransition-enter-active {
  transition: all 1s ease;
}
.myTransition-leave-active {
  transition: all 8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.myTransition-enter, .myTransition-leave-to
/* .slide-fade-leave-active for <2.1.8 */ {
  transform: translateX(30px);
  opacity: 0;
}
</style>