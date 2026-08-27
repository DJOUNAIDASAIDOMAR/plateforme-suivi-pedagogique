<style>
    /*
    |--------------------------------------------------------------------------
    | ACCESSIBILITÉ
    |--------------------------------------------------------------------------
    */

    .accessibility-tools {
        position: fixed;
        right: 20px;
        bottom: 20px;
        z-index: 9999;
    }

    .accessibility-button {
        padding: 11px 15px;
        border: 2px solid #ffffff;
        border-radius: 10px;
        background: #1d4ed8;
        color: #ffffff;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        box-shadow:
            0 4px 15px
            rgba(0, 0, 0, 0.18);
    }

    .accessibility-panel {
        display: none;
        position: absolute;
        right: 0;
        bottom: 55px;
        width: 240px;
        padding: 16px;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        background: #ffffff;
        box-shadow:
            0 8px 25px
            rgba(0, 0, 0, 0.18);
    }

    .accessibility-panel.open {
        display: block;
    }

    .accessibility-panel h2 {
        margin: 0 0 12px;
        color: #111827;
        font-size: 17px;
    }

    .accessibility-option {
        width: 100%;
        margin-bottom: 8px;
        padding: 10px;
        border: 1px solid #2563eb;
        border-radius: 8px;
        background: #ffffff;
        color: #1d4ed8;
        font-weight: 700;
        cursor: pointer;
    }

    .accessibility-option:last-child {
        margin-bottom: 0;
    }

    .accessibility-option:hover,
    .accessibility-option:focus {
        background: #eff6ff;
    }


    /*
    |--------------------------------------------------------------------------
    | NAVIGATION AU CLAVIER
    |--------------------------------------------------------------------------
    */

    a:focus-visible,
    button:focus-visible,
    input:focus-visible,
    select:focus-visible,
    textarea:focus-visible {
        outline: 4px solid #f59e0b !important;
        outline-offset: 3px;
    }


    /*
    |--------------------------------------------------------------------------
    | TAILLE DU TEXTE
    |--------------------------------------------------------------------------
    */

    html.accessibility-large-text {
        font-size: 120%;
    }

    html.accessibility-extra-large-text {
        font-size: 140%;
    }


    /*
    |--------------------------------------------------------------------------
    | CONTRASTE ÉLEVÉ
    |--------------------------------------------------------------------------
    */

    body.high-contrast {
        background: #000000 !important;
        color: #ffffff !important;
    }

    body.high-contrast main,
    body.high-contrast section,
    body.high-contrast article,
    body.high-contrast aside {
        color: #ffffff;
    }

    body.high-contrast a {
        color: #ffff00 !important;
    }

    body.high-contrast input,
    body.high-contrast textarea,
    body.high-contrast select {
        border: 2px solid #ffffff !important;
        background: #000000 !important;
        color: #ffffff !important;
    }

    body.high-contrast button {
        border-color: #ffffff !important;
    }

    body.high-contrast .accessibility-panel {
        border: 2px solid #ffffff;
        background: #000000;
        color: #ffffff;
    }

    body.high-contrast .accessibility-panel h2 {
        color: #ffffff;
    }

    body.high-contrast .accessibility-option {
        border: 2px solid #ffffff;
        background: #000000;
        color: #ffff00;
    }


    /*
    |--------------------------------------------------------------------------
    | LIEN D'ÉVITEMENT
    |--------------------------------------------------------------------------
    */

    .skip-link {
        position: fixed;
        top: 10px;
        left: -9999px;
        z-index: 10000;
        padding: 12px 16px;
        border: 3px solid #000000;
        border-radius: 8px;
        background: #ffffff;
        color: #000000 !important;
        font-weight: 700;
        text-decoration: none;
    }

    .skip-link:focus {
        left: 10px;
    }
</style>


<a
    href="#contenu-principal"
    class="skip-link"
>
    Aller directement au contenu
</a>


<div class="accessibility-tools">

    <div
        id="accessibility-panel"
        class="accessibility-panel"
        aria-hidden="true"
    >

        <h2>
            Accessibilité
        </h2>

        <button
            type="button"
            class="accessibility-option"
            id="decrease-text"
        >
            A− Réduire le texte
        </button>

        <button
            type="button"
            class="accessibility-option"
            id="increase-text"
        >
            A+ Agrandir le texte
        </button>

        <button
            type="button"
            class="accessibility-option"
            id="contrast-button"
            aria-pressed="false"
        >
            ◐ Contraste élevé
        </button>

        <button
            type="button"
            class="accessibility-option"
            id="reset-accessibility"
        >
            ↻ Réinitialiser
        </button>

    </div>


    <button
        type="button"
        id="accessibility-button"
        class="accessibility-button"
        aria-expanded="false"
        aria-controls="accessibility-panel"
    >
        ♿ Accessibilité
    </button>

</div>


<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const html =
                document.documentElement;

            const body =
                document.body;

            const button =
                document.getElementById(
                    'accessibility-button'
                );

            const panel =
                document.getElementById(
                    'accessibility-panel'
                );

            const increaseButton =
                document.getElementById(
                    'increase-text'
                );

            const decreaseButton =
                document.getElementById(
                    'decrease-text'
                );

            const contrastButton =
                document.getElementById(
                    'contrast-button'
                );

            const resetButton =
                document.getElementById(
                    'reset-accessibility'
                );


            /*
            |--------------------------------------------------------------------------
            | RESTAURER LES PRÉFÉRENCES
            |--------------------------------------------------------------------------
            */

            let textSize =
                parseInt(
                    localStorage.getItem(
                        'accessibilityTextSize'
                    ) || '0'
                );


            const highContrast =
                localStorage.getItem(
                    'accessibilityContrast'
                ) === 'true';


            function applyTextSize() {

                html.classList.remove(
                    'accessibility-large-text',
                    'accessibility-extra-large-text'
                );


                if (textSize === 1) {

                    html.classList.add(
                        'accessibility-large-text'
                    );
                }


                if (textSize >= 2) {

                    html.classList.add(
                        'accessibility-extra-large-text'
                    );
                }
            }


            applyTextSize();


            if (highContrast) {

                body.classList.add(
                    'high-contrast'
                );

                contrastButton.setAttribute(
                    'aria-pressed',
                    'true'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | OUVRIR / FERMER
            |--------------------------------------------------------------------------
            */

            button.addEventListener(
                'click',
                function () {

                    const isOpen =
                        panel.classList.toggle(
                            'open'
                        );


                    button.setAttribute(
                        'aria-expanded',
                        isOpen
                            ? 'true'
                            : 'false'
                    );


                    panel.setAttribute(
                        'aria-hidden',
                        isOpen
                            ? 'false'
                            : 'true'
                    );
                }
            );


            /*
            |--------------------------------------------------------------------------
            | AGRANDIR
            |--------------------------------------------------------------------------
            */

            increaseButton.addEventListener(
                'click',
                function () {

                    if (textSize < 2) {
                        textSize++;
                    }


                    localStorage.setItem(
                        'accessibilityTextSize',
                        textSize
                    );


                    applyTextSize();
                }
            );


            /*
            |--------------------------------------------------------------------------
            | RÉDUIRE
            |--------------------------------------------------------------------------
            */

            decreaseButton.addEventListener(
                'click',
                function () {

                    if (textSize > 0) {
                        textSize--;
                    }


                    localStorage.setItem(
                        'accessibilityTextSize',
                        textSize
                    );


                    applyTextSize();
                }
            );


            /*
            |--------------------------------------------------------------------------
            | CONTRASTE
            |--------------------------------------------------------------------------
            */

            contrastButton.addEventListener(
                'click',
                function () {

                    body.classList.toggle(
                        'high-contrast'
                    );


                    const enabled =
                        body.classList.contains(
                            'high-contrast'
                        );


                    localStorage.setItem(
                        'accessibilityContrast',
                        enabled
                    );


                    contrastButton.setAttribute(
                        'aria-pressed',
                        enabled
                            ? 'true'
                            : 'false'
                    );
                }
            );


            /*
            |--------------------------------------------------------------------------
            | RÉINITIALISER
            |--------------------------------------------------------------------------
            */

            resetButton.addEventListener(
                'click',
                function () {

                    textSize = 0;


                    html.classList.remove(
                        'accessibility-large-text',
                        'accessibility-extra-large-text'
                    );


                    body.classList.remove(
                        'high-contrast'
                    );


                    contrastButton.setAttribute(
                        'aria-pressed',
                        'false'
                    );


                    localStorage.removeItem(
                        'accessibilityTextSize'
                    );


                    localStorage.removeItem(
                        'accessibilityContrast'
                    );
                }
            );


            /*
            |--------------------------------------------------------------------------
            | TOUCHE ÉCHAP
            |--------------------------------------------------------------------------
            */

            document.addEventListener(
                'keydown',
                function (event) {

                    if (
                        event.key === 'Escape'
                        &&
                        panel.classList.contains(
                            'open'
                        )
                    ) {

                        panel.classList.remove(
                            'open'
                        );


                        panel.setAttribute(
                            'aria-hidden',
                            'true'
                        );


                        button.setAttribute(
                            'aria-expanded',
                            'false'
                        );


                        button.focus();
                    }
                }
            );
        }
    );
</script>