# Aveonline Widget WhatsApp

**Contribuyentes:** Francisco Blanco
**Versión estable:** 2.0.1
**Requiere al menos:** WordPress 5.0
**Probado hasta:** WordPress 5.9
**Licencia:** GPLv2 o posterior
**URI de licencia:** https://www.gnu.org/licenses/gpl-2.0.html

Plugin de WordPress que agrega un widget flotante de WhatsApp para Elementor, diseñado para la empresa colombiana **Aveonline**. El widget permite a los visitantes enviar sus datos de contacto y ser redirigidos a una conversación de WhatsApp, integrando la información con los sistemas AveChat (LucidBot) y Aveonline CRM.

---

## Descripción

Aveonline Widget WhatsApp proporciona un widget para **Elementor** que muestra un botón flotante con el ícono de WhatsApp en la esquina inferior derecha del sitio. Al hacer clic, se despliega un panel con:

- **Avatar:** Foto del agente y nombre con indicador "Online".
- **Mensajes:** Lista de mensajes personalizables (wysiwyg) con soporte para clases CSS.
- **Formulario:** Campos de nombre, código de país y teléfono, más un botón de envío.
- **Integración CRM:** Los datos se envían a la API de AveChat y Aveonline para crear contactos y leads automáticamente.
- **Detección de campaña:** Reconoce automáticamente la campaña activa desde la URL o parámetro `?campana=`.

### Características

- Widget flotante fijo en la parte inferior derecha con animación de apertura/cierre.
- Totalmente personalizable desde el editor de Elementor (contenido y estilos).
- Panel de estilos completo: color, tipografía, sombra, fondo, bordes, border-radius, padding y margin para cada elemento.
- Selector de código de país con más de 100 países.
- Integración con **AveChat (LucidBot)** para crear y gestionar usuarios.
- Integración con **Aveonline API** para creación de leads.
- **Auto-actualización desde GitHub:** El plugin verifica automáticamente nuevas versiones en el repositorio de GitHub cada 10 minutos.
- Página de administración para configurar el Token de acceso a la API de AveChat.

---

## Instalación

1. Descarga el archivo `.zip` desde el [repositorio de GitHub](https://github.com/franciscoblancojn/aveonline-widget-whatsapp).
2. Ve al escritorio de WordPress en **Plugins → Añadir nuevo**.
3. Haz clic en **Subir plugin**.
4. Selecciona el archivo `.zip` descargado y haz clic en **Instalar ahora**.
5. Una vez instalado, activa el plugin.
6. Ve a **Aveonline Whatsapp** en el menú de administración para configurar el **Token** de la API de AveChat.
7. En el editor de Elementor, busca el widget **"Aveonline Whatsapp"** en la categoría "General" y agrégalo a tu página.

---

## Configuración

### Token de API

1. Ve a **Aveonline Whatsapp** en el panel de administración de WordPress.
2. Ingresa el token de acceso proporcionado por AveChat (LucidBot) en el campo **Token**.
3. Haz clic en **Guardar**.

### Uso en Elementor

El widget **"Aveonline Whatsapp"** (ID: `ave_form_whatsapp`) se encuentra en la categoría **General** del editor de Elementor.

#### Pestaña Contenido

| Sección | Controles |
|---|---|
| **Avatar** | Nombre del agente, imagen de avatar (por defecto `avatar.png`) |
| **Mensajes** | Repetidor de mensajes WYSIWYG con clase CSS opcional |
| **Formulario** | Placeholders de nombre, código y teléfono; texto descriptivo; texto del botón; lista de campañas permitidas para detección por URL |
| **API** | URL del endpoint API (por defecto `https://avechat-hubspot.api.aveonline.co/api/form-campana/ave-chat/create-contact`), URL de redirección de WhatsApp |

#### Pestaña Estilo

Estilos personalizables para cada elemento:
- **Content** (contenedor principal)
- **Avatar Name** (nombre del agente)
- **Avatar Online** (indicador "Online")
- **Avatar Close** (botón de cerrar)
- **Messages** (burbujas de mensaje)
- **Inputs** (campos del formulario)
- **Text** (texto descriptivo)
- **Boton** (botón de envío)

Cada elemento permite configurar: Color, Tipografía, Sombra de caja, Fondo, Bordes, Border-radius, Padding y Margin.

---

## Endpoints REST API

### `POST /wp-json/avww/send-contact`

Endpoint público que recibe los datos del formulario del widget.

**Body (JSON):**
```json
{
  "name": "Nombre del contacto",
  "phone": "3001234567",
  "code": "+57",
  "campana": "envios-nacionales"
}
```

**Respuesta exitosa (200):**
```json
{
  "success": true,
  "message": "Contacto creado correctamente.",
  "data": {
    "avechat": { ... },
    "ave": { ... },
    "custom_fields": { ... }
  }
}
```

**Flujo interno:**
1. Valida que `name` y `phone` no estén vacíos.
2. Busca o crea un usuario en **AveChat** (LucidBot) usando el teléfono como ID.
3. Crea un **lead** en **Aveonline** a través de su API de onboarding.
4. Guarda campos personalizados en AveChat (`id_user_ave`, `id_lead`, `url_ave_pre_register`, `id_empresa_ave`, `campana`).

---

## Detección de Campañas

El widget detecta automáticamente la campaña activa de dos maneras:
1. **Parámetro URL:** `?campana=nombre-campana`
2. **Ruta URL:** Escanea `window.location.href` para detectar segmentos de ruta que coincidan con la lista de campañas configurada en el widget.

Las campañas preconfiguradas incluyen: `envios-nacionales`, `envios-nacionales-ads`, `crea-tu-cuenta-billetera-aveonline`, entre otras.

---

## Auto-actualización desde GitHub

El plugin incluye un sistema de actualización automática que consulta el repositorio de GitHub cada 10 minutos. Si hay una nueva versión disponible, aparecerá en la pantalla de **Plugins** de WordPress con un enlace **"Actualizar"**.

---

## Estructura del Plugin

```
aveonline-widget-whatsapp/
├── index.php                      # Archivo principal (plugin header, constantes, hooks)
├── update.php                     # Auto-actualizador desde GitHub
├── package.json                   # Scripts de release y versionado
├── README.md                      # Este archivo
└── src/
    ├── widget.php                 # Clase del widget de Elementor (AVWW_AveFormWhatsapp)
    ├── api/
    │   ├── _.php                  # Cargador de la API
    │   └── sendContact.php        # Endpoint REST + lógica de integración AveChat/Aveonline
    ├── component/
    │   ├── _.php                  # Cargador de componentes
    │   ├── widget.php             # Shell del widget flotante
    │   ├── top.php                # Barra superior con avatar
    │   ├── messages.php           # Burbujas de mensajes
    │   └── form.php               # Formulario de contacto + JavaScript
    ├── page/
    │   ├── _.php                  # Cargador de página de administración
    │   ├── add.php                # Registro del menú de administración
    │   └── page.php               # Página de configuración (Token AveChat)
    └── img/
        └── avatar.png             # Imagen de avatar por defecto
```

---

## Desarrollador

- **Nombre:** Francisco Blanco
- **Sitio web:** https://franciscoblanco.vercel.app/
- **Correo electrónico:** blancofrancisco34@gmail.com
- **GitHub:** https://github.com/franciscoblancojn/aveonline-widget-whatsapp

---

## Licencia

Este plugin se distribuye bajo los términos de la Licencia Pública General de GNU v2.0 o posterior.
