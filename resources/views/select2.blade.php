<script>
    $(document).ready(function() {
        $('.select2').select2({     
            theme: 'bootstrap',
            placeholder: 'Selectionner',
            language: {
                  noResults: function() {
                    return "Aucun résultat trouvé";
                }
            },
            width: '100%',
        });

        // Vérifiez la version de jQuery
        // if (typeof jQuery !== 'undefined') {
        //     console.log("jQuery version:", jQuery.fn.jquery);
        // } else {
        //     console.error("jQuery n'est pas chargé.");
        // }

        // Vérifiez la version de Select2
        // if ($.fn.select2) {
        //     console.log("Select2 version:", $.fn.select2.defaults.version);
        //     console.log($.fn.tooltip.Constructor.VERSION);

        //     $('.select2').select2({
        //         tags: true,
        //         theme: 'bootstrap',
        //         placeholder: 'Selectionner',
        //         language: {
        //             noResults: function() {
        //                 return "Aucun résultat trouvé";  // Modifier le message ici
        //             }
        //         },
        //         width: 'resolve',
        //     });

        // } else {
        //     console.error("Select2 n'est pas chargé correctement.");
        // }
        
    });
</script>