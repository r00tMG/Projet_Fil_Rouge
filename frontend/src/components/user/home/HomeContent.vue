<script setup>
import FooterHome from "@/components/user/home/FooterHome.vue";
import '@vuepic/vue-datepicker/dist/main.css'
import '@vueform/multiselect/themes/default.css';
import publish from '@/assets/images/publish.svg'
import payment from '@/assets/images/payment.svg'
import message from '@/assets/images/message.svg'
import connect from '@/assets/images/connect.svg'
import recherche from '@/assets/images/recherche.svg'
import reserver from '@/assets/images/reserver.svg'

</script>

<template>
  <main>
    <div class="container corde rounded-circle"></div>
    <div class="container-fluid px-0">
      <div class="container m-auto p-5">
        <div class="w-75 m-auto m-5 p-5 text-center">
          <h3>
            <span class="text-success">Publiez</span> vos annonces,
            <span class="text-success">Gérez</span> les réservations, et
            <span class="text-success">Connectez-vous</span> rapidement avec vos clients
            grâce à <em class="text-success">MonGP</em>
          </h3>
        </div>
        <div class="container">
          <div class="row m-auto px-4 py-5   ">
            <div class="row m-auto">
              <div class="col-md-2 m-auto text-center">
                <router-link to="/search/annonces" class="btn btn-success text-center shadow-sm rounded-5 mt-4 p-3">
                  Commencer
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid mt-3 bg-light">
      <div class="container w-75  d-flex m-4 p-5 gap-5">
        <div class="rounded-circle shadow p-3 bg-light">
          <svg stroke="currentColor" class="text-success" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" height="3.1em" width="3.1em" xmlns="http://www.w3.org/2000/svg">
            <path d="M624 448H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h608c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16zM80.55 341.27c6.28 6.84 15.1 10.72 24.33 10.71l130.54-.18a65.62 65.62 0 0 0 29.64-7.12l290.96-147.65c26.74-13.57 50.71-32.94 67.02-58.31 18.31-28.48 20.3-49.09 13.07-63.65-7.21-14.57-24.74-25.27-58.25-27.45-29.85-1.94-59.54 5.92-86.28 19.48l-98.51 49.99-218.7-82.06a17.799 17.799 0 0 0-18-1.11L90.62 67.29c-10.67 5.41-13.25 19.65-5.17 28.53l156.22 98.1-103.21 52.38-72.35-36.47a17.804 17.804 0 0 0-16.07.02L9.91 230.22c-10.44 5.3-13.19 19.12-5.57 28.08l76.21 82.97z">
            </path>
          </svg>
        </div>
        <div class="">
          <h4 class="hover:underline mt-2">Mode opératoire</h4>
          <div class="h4 pb-2 width mb-4 border-bottom border-success"></div>
        </div>
      </div>
      <div class="container w-80 m-auto">
        <div class="row p-5 justify-content-center">
          <div class="col-md-4">
              <div class="row mt-5">
                <p class="">
                  <span class="step-number me-3">1</span>
                  <span class="text-success fs-3">Connexion</span>
                </p>
                <p class="w-75 ">
                  En tant que bénéficiaire de la gratuité partielle (GP), accédez facilement à votre compte en quelques clics. Connectez-vous avec vos identifiants pour gérer vos annonces, suivre les kilos disponibles et interagir avec les clients intéressés. Profitez d'une interface simple et sécurisée, conçue pour vous permettre de publier rapidement et de manière efficace.
                </p>
              </div>
          </div>
            <img :src="connect" class="col-md-4 shadow border rounded-5 img-fluid" alt="#">
        </div>
      </div>
      <div class="container w-80 m-auto">
        <div class="row p-5 justify-content-center">
          <div class="col-md-4">
            <div class="row mt-5">
              <p class="">
                <span class="step-number me-3">2</span>
                <span class="text-success fs-3">Publier</span>
              </p>
              <p class="w-75 ">
                Une fois connecté, publiez vos kilos disponibles en quelques étapes simples.
                Ajoutez des détails tels que la quantité, la date et les lieux de récupération,
                et mettez vos annonces à la disposition des utilisateurs qui recherchent des produits à récupérer.
                Gagnez du temps grâce à un système de publication optimisé qui vous permet de gérer vos kilos de manière fluide
                et intuitive.
              </p>
            </div>
          </div>
          <img :src="publish" class="col-md-4 shadow border rounded-5 img-fluid" alt="#">
        </div>
      </div>
      <div class="container w-80 m-auto">
        <div class="row p-5 justify-content-center">
          <div class="col-md-4">
            <div class="row mt-5">
              <p class="">
                <span class="step-number me-3">3</span>
                <span class="text-success fs-3">Rechercher</span>
              </p>
              <p class="w-75 ">
                Grâce à notre système de recherche intuitif,
                trouvez rapidement les kilos disponibles mis en ligne par les bénéficiaires de la gratuité partielle (GP).
                Filtrez par origine, destination, et date pour repérer les offres qui correspondent à vos besoins.
                Chaque annonce est mise à jour en temps réel, vous garantissant une recherche fluide et précise.
              </p>
            </div>
          </div>
          <img :src="recherche" class="col-md-4 shadow border rounded-5 img-fluid" alt="#">
        </div>
      </div>
      <div class="container w-80 m-auto">
        <div class="row p-5 justify-content-center">
          <div class="col-md-4">
            <div class="row mt-5">
              <p class="">
                <span class="step-number me-3">4</span>
                <span class="text-success fs-3">Réserver</span>
              </p>
              <p class="w-75 ">
                Une fois que vous avez trouvé les kilos qui vous intéressent,
                réservez-les en quelques clics. Sélectionnez la quantité
                et recevez une confirmation immédiate de votre réservation.
                Planifiez vos récupérations en toute simplicité grâce à notre interface de réservation efficace et transparente.
              </p>
            </div>
          </div>
          <img :src="reserver" class="col-md-4 shadow border rounded-5 img-fluid" alt="#">
        </div>
      </div>
      <div class="container w-80 m-auto">
        <div class="row p-5 justify-content-center">
          <div class="col-md-4">
            <div class="row mt-5">
              <p class="">
                <span class="step-number me-3">5</span>
                <span class="text-success fs-3">Payer par carte</span>
              </p>
              <p class="w-75 ">
                Effectuez vos paiements directement en ligne avec une carte de crédit ou de débit en toute sécurité.
                Notre plateforme de paiement est rapide et fiable, vous permettant de finaliser vos réservations sans souci.
                Vos informations de paiement sont protégées grâce à des standards de sécurité de pointe.
              </p>
            </div>
          </div>
          <img :src="payment" class="col-md-4 shadow border rounded-5 img-fluid" alt="#">
        </div>
      </div>
      <div class="container w-80 m-auto">
        <div class="row p-5 justify-content-center">
          <div class="col-md-4">
            <div class="row mt-5">
              <p class="">
                <span class="step-number me-3">6</span>
                <span class="text-success fs-3">Communication</span>
              </p>
              <p class="w-75 ">
                Restez en contact avec les GP et les autres utilisateurs grâce à notre système de messagerie intégré.
                Posez des questions, organisez la récupération des kilos ou échangez des informations en toute simplicité.
                Une communication fluide et rapide pour un service transparent et réactif.
              </p>
            </div>
          </div>
          <img :src="message" class="col-md-4 shadow border rounded-5 img-fluid" alt="#">
        </div>
      </div>
    </div>
  </main>
  <FooterHome />
</template>


