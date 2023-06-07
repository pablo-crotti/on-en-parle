<template>
    
    <div class="program-wrapper">
        <div class="program-header">
            <img class="program-img" :src="program.image"/>
            <div class="program-title">
                <span>Replay</span>
                <h2>{{ program.title }}</h2>
            </div>
        </div>

        <div class="program-body">
            <p class="program-desc">{{ truncateParagraph(program.description, 13)}}</p>

            <div class="program-bottom">
                <div class=" program-interactions" >
                    <span class="material-symbols-outlined">
                        forum
                    </span>

                   <span>{{ room.length }}</span>   
                </div>

                <span class="program-date">{{ program.broadcast_date }}</span>
                
                <span class="material-symbols-outlined">
                        play_circle
                 </span>
            </div>
        </div>
    </div>  
</template>

<script>
import axios from 'axios';

    export default {
        data() {
            return {
                room: {
                    type: Object
                }
            }
        },
        props: {
            program: {
                type: Object
            }
        },
        methods: {
            truncateParagraph(paragraph, wordCount) {
  // Sépare le paragraphe en mots individuels
  const words = paragraph.split(' ');

  // Vérifie si le paragraphe est plus court que le nombre de mots souhaité
  if (words.length <= wordCount) {
    return paragraph; // Retourne le paragraphe complet tel quel
  }

  // Tronque le tableau de mots à la longueur souhaitée
  const truncatedWords = words.slice(0, wordCount);

  // Rejoindre les mots tronqués en une seule chaîne avec des espaces
  const truncatedParagraph = truncatedWords.join(' ');

  // Ajouter une ellipse à la fin du paragraphe tronqué
  const finalParagraph = truncatedParagraph + '...';

  return finalParagraph;
}
        },
        created() {
            axios.get(`/chat/room/${this.program.id}/messages`).then(response => {
            
                this.room = response.data;
                
            
            });
    
        
        }
    }   
</script>