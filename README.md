# 🧪 Proyecto Eventos C - Plataforma de Gestión de Eventos

> **Desarrollado por:**  
> 👤 **Edson DO (TriVisioN)**  

---

## 🚀 Descripción del Proyecto

**Eventos C** no es solo una página web; es una **solución integral** diseñada para acabar con el caos en la gestión de eventos. Olvídate de las hojas de cálculo desactualizadas y los correos perdidos. Esta plataforma centraliza la administración de reservas, clientes, proveedores y recursos en un entorno web **robusto, rápido y escalable**.

Construido desde cero con **PHP Nativo**, implementando una arquitectura **MVC (Modelo-Vista-Controlador)** artesanal, este sistema demuestra un control total sobre el flujo de datos y la lógica de negocio, sin depender de frameworks pesados. 

---

## 🛠️ Tecnologías Utilizadas (El Arsenal)

Este proyecto corre sobre una base sólida de tecnologías estándar de la industria:

*   **Backend:** <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" /> (PHP Puro, POO, PDO para seguridad máxima en BBDD)
*   **Frontend:** <img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white" /> <img src="https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white" /> (Diseño Responsive, CSS Grid/Flexbox)
*   **Base de Datos:** <img src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white" /> (Modelo Relacional optimizado)
*   **Servidor:** <img src="https://img.shields.io/badge/Apache-D22128?style=for-the-badge&logo=apache&logoColor=white" /> (XAMPP / Laragon)
*   **Control de Versiones:** <img src="https://img.shields.io/badge/GIT-E44C30?style=for-the-badge&logo=git&logoColor=white" />

---

## 🌟 Módulos y Funcionalidades

### 🔐 1. Autenticación y Seguridad
*   **Login Seguro:** Sistema de validación de credenciales con encriptación de contraseñas (Hash).
*   **Registro de Usuarios:** Permite a nuevos clientes unirse a la plataforma en segundos.
*   **Control de Sesiones:** Protección de rutas para asegurar que solo usuarios autorizados accedan a ciertas áreas.
*   **Roles de Usuario:** Diferenciación clara entre **Administradores** (Dioses del sistema) y **Clientes** (Usuarios finales).

### 🏢 2. Zona de Cliente (Frontend)
Una experiencia fluida para que el usuario organice su evento soñado:

*   **🏠 Landing Page:** Portada atractiva que invita a la conversión.
*   **📅 Catálogo de Sedes:** Visualización de espacios disponibles con fotos y descripciones.
*   **🛠️ Configuración de Reserva:**
    *   Selección de fechas inteligente (evita solapamientos).
    *   Personalización de detalles del evento.
*   **➕ Servicios Adicionales:** Carrito de compras para agregar catering, sonido, iluminación, etc.
*   **💳 Pasarela de Pagos (Simulada):** Flujo completo de confirmación de reserva y generación de "Ticket".
*   **📜 Historial de Reservas:** El cliente puede ver todos sus eventos pasados y futuros en un solo lugar.

### ⚙️ 3. Panel de Administración (Backoffice)
El centro de mando para el equipo de **Eventos C**:

*   **📊 Dashboard:** Vista general del estado del sistema.
*   **👥 Gestión de Clientes (CRUD):**
    *   Ver, editar y eliminar cuentas de usuarios.
    *   Análisis de la base de datos de clientes.
*   **🏗️ Gestión de Sedes (CRUD):**
    *   Administrar los locales, capacidades y precios base.
    *   Subida de información detallada.
*   **📦 Gestión de Recursos e Inventario (CRUD):**
    *   Control total sobre mesas, sillas, equipos, etc.
*   **🤝 Gestión de Proveedores (CRUD):**
    *   Base de datos de socios externos (catering, seguridad, etc.).
*   **🎉 Gestión de Eventos & Reservas:**
    *   Supervisión de todas las reservas activas.
    *   Capacidad de cancelar o modificar eventos desde el lado administrativo.
*   **🔧 Gestión de Servicios:**
    *   Alta, baja y modificación del catálogo de servicios ofrecidos.

---

## 📂 Estructura del Proyecto (Arquitectura MVC)

El proyecto sigue una estructura limpia y organizada para facilitar el mantenimiento:

```
ProyectoEventosC/
├── 📂 Backend/              # Lógica del Servidor
│   ├── 📂 config/           # Conexión a BD (Conexion.php)
│   ├── 📂 controllers/      # Controladores (Cerebro de la lógica, ej: ReservaControlador.php)
│   └── 📂 models/           # Modelos (Acceso a Datos, ej: Usuario.php, Reserva.php)
│
├── 📂 Frontend/             # Interfaz de Usuario
│   ├── 📂 assets/           # Recursos estáticos (CSS, JS, Imágenes)
│   └── 📂 views/            # Vistas HTML/PHP
│       ├── 📂 admin/        # Vistas del Panel de Control
│       ├── 📂 client/       # Vistas del área pública/cliente
│       └── 📂 layouts/      # Cabeceras y pies de página reutilizables
│
├── 📄 index.php             # Front Controller (Punto de entrada único / Router)
├── 📄 .htaccess             # Configuración de servidor (Redirecciones amigables)
└── 📄 README.md             # Este archivo sensual que estás leyendo
```

---

## 🏁 Cómo Desplegar este Proyecto

Sigue estos pasos para tener **Eventos C** corriendo en tu máquina local:

1.  **Clona el repositorio:**
    ```bash
    git clone https://github.com/TuUsuario/ProyectoEventosC.git
    ```
2.  **Mueve la carpeta:**
    *   Copia la carpeta del proyecto a `C:/laragon/www/` (si usas Laragon) o `C:/xampp/htdocs/` (si usas XAMPP).
3.  **Importa la Base de Datos:**
    *   Abre tu gestor SQL (phpMyAdmin, HeidiSQL, DBeaver).
    *   Crea una base de datos llamada `eventosc_db` (o revisa `Backend/config/Conexion.php` para ver el nombre exacto).
    *   Importa el script SQL que encontrarás en la carpeta `bd/` (si existe) o pídele el script al administrador.
4.  **Configura la Conexión:**
    *   Abre `Backend/config/Conexion.php`.
    *   Asegúrate de que las credenciales (usuario, password, host) coincidan con tu servidor local.
5.  **Disfrute**
    *   Abre tu navegador y ve a: `http://localhost/ProyectoEventosC`
(Nota de Edson: Hay que cambiar esta sección, solo me falta desplegar, pero no lo ví necesario XD)
---

## 📞 Contacto & Créditos

**Autor Principal:** Edson DO  
**Organización:** Tochi's Dev Team (El solito)


