# Decidir Payment Gateway for WordPress
Plugin de integracion con PRISMA Decidir, para WooCommerce

### Requisitos
* Wordpress `>= 5.8.3`,` <= 5.9.3`
* WooCommerce `>= 6.0`
* PHP `>=7.4 < 8`

## Instalación
Para la instalación puede optar por subir el plugin a traves del Administrador. O bien, copiar el plugin manualmente dentro de la instalación de WordPress.
Recuerde: no es necesario realizarlo de las dos formas, escoja la forma que le sea mas conveniente.

### Via Administrador
1. Ingrese al panel de Administración de WordPress
2. Ingrese a la sección _Plugins > Agregar nuevo_
3. Haga click en el botón _Subir plugin_, que se encuentra a la derecha del titulo de la pagina.
4. En la sección que se despliega, haga click en el botón _Seleccionar..._ y, desde el cuadro de dialogo, seleccione el archivo `.zip` del plugin.
5. Haga click en el boton _Instalar ahora_
6. Cuando haya finalizado, dirijase a la sección _Plugins > Plugins instalados_ y haga click en el link _Activar_ que se encuentra debajo del nombre del plugin: _Decidir Payment Gateway for WooCommerce_.

### Copia Manual
1. Descomprimir el archivo wc-gateway-decidir.zip
2. Copiar carpeta `wc-gateway-decidir` al directorio de plugins de su instalación de WordPress: `wp-content/plugins/`
3. Ingrese al panel de Administración de WordPress
4. Dirijase a la sección _Plugins > Plugins instalados_ y haga click en el link _Activar_ que se encuentra debajo del nombre del plugin: _Decidir Payment Gateway for WooCommerce_.



## Configuración
### Credenciales y datos de comercio

1. Ingresar al menu de configuración del plugin desde: <em>WooCommerce -> Ajustes -> Pagos </em>
2. Habilitar el plugin desde el boton <strong>Habilitar</strong>
3. Presionar el botón </strong>configurar</strong>, en caso que al habilitar el medio de pago no se redirija automaticamente a la sección de configuración
4. Ingresar los datos solicitados en la pantalla

Test mode (si/no)
Enable Cybersourse (Si/No)

Test Publishable Key
Test Private Key

•••••••••••••••••••••••••••••••
Live Publishable Key
Live Private Key

••••••••••••••••••••••••••••••••
Establishment ID

••••••••••••••••••••••••••••••••
Maxima Cantidad de Cuotas habilitadas

Seleccionar Tarjetas Habilitadas  del listado

## Consideraciones
El formulario no será presentado en el Checkout, si:
- El plugin está deshabilitado en la configuración de WooCommerce (ver WC config: WooCommerce > Ajustes > Pagos > Medio de Pago Decidir: Activado - Si)
- WooCommerce está configurado en otra moneda diferente a _Peso Argentino ($)_ (ver WC config: WooCommerce > Ajustes > General > Opciones de Moneda > Moneda)
- El plugin Decidir no contiene una Promoción configurada correctamente (ver sección: Promociones > Consideraciones en este README)

## Menú lateral
En el menú lateral de su Backoffice, se habilitará el grupo "Decidir", donde podrá configurar tarjetas de crédito/débito, bancos y promociones con sus respectivos planes.

### Bancos
En el menú _Decidir > Bancos_, podrá administrar los bancos o emisores de tarjetas con los que necesite operar.

**Agregar Banco**
- En la esquina superior derecha de la tabla de bancos presione en el botón add new/agregar nuevo.
- Ingrese los datos requeridos en el formulario que se desplegará.
- Puede cargar una imagen desde su computadora, y activar o desactivar el banco.
- Presione guardar para aplicar los cambios.
​

**Editar Banco**
- En la tabla de bancos, sobre la fila correspondiente, presione el botón _Editar_.
- Se desplegará el mismo formulario del ítem anterior. Aquí puede modificar los datos a su gusto.

​
**Eliminar Banco**
- En la tabla de bancos, sobre la fila correspondiente, presione el botón "Eliminar".
- Se le pedirá confirmar la eliminación del banco.
​

### Tarjetas
En el menú _Decidir > Tarjetas_, podrá administrar los tipos de tarjetas con las que necesite operar.
​

**_Agregar_ Tarjeta**
- En la esquina superior derecha de la tabla de tarjetas presione en el botón _Agregar_.
- Ingrese los datos requeridos en el formulario que se desplegará.
- Presione guardar para aplicar los cambios.
​

**Editar Tarjeta**
- En el listado de Tarjetas, sobre la fila correspondiente, presione el botón _Editar_.
- Se desplegará el mismo formulario del ítem anterior. Aquí puede modificar los datos a su gusto.
​

**Eliminar Tarjeta**
- En la tabla de tarjetas, sobre la fila correspondiente, presione el botón "Eliminar".
- Se le pedirá confirmar la eliminación de la tarjeta.​
​

### Promociones
En el menú _Decidir > Promociones_, podrá administrar las promociones y condiciones de cuotas sobre los pedidos realizados. Defina los planes de cuotas en esta sección según sea necesario.
​

**Agregar Promoción**
- Haga click en el link _Agregar nuevo Plan_.
- Ingrese los datos requeridos en el formulario que se desplegará.
- Deberá seleccionar las tarjetas y bancos a los que alcanzará la promoción.
- Deberá seleccionar en qué días de la semana esta promoción estará disponible y desde y hasta qué fechas.
- Si su PrestaShop maneja multitienda, deberá seleccionar sobre cuál de ellas ésta promoción será aplicada.
- Elija una posición para darle mayor prioridad a esta promoción.
- Puede activar o desactivar la promoción.
​

**Editar Promoción**
- En la tabla de promociones, sobre la fila correspondiente, presione el botón "Editar".
- Se desplegará el mismo formulario del ítem anterior. Aquí puede modificar los datos a su gusto.
​

**Eliminar Promoción**
- En la tabla de promociones, sobre la fila correspondiente, presione el botón "Eliminar".
- Se le pedirá confirmar la eliminación de la promoción.
​

#### Configuración de Planes para una Promoción
En el mismo formulario de edición y creación de una Promoción, tendrá disponible una tabla de Planes e Intereses. Estas serán las cuotas a mostrar al comprador.
​

#### Valores de un Plan
| Campo  | Valor  | Descripcion  |
|---|---|---|
| Periodo | numero entero | El numero de Cuota de la Promoción |
| Coeficiente  | entero o decimal con punto | El interes a ser aplicado a la cuota del Plan |
| TEA | decimal con punto | no se utiliza actualmente, colocar `0` |
| CFT | decimal con punto | no se utiliza actualmente, colocar `0` |
| Valor a Enviar | numero entero | El numero de cuota de la Promoción a enviar al gateway de pago. <br/>Usualmente este valor es exactamente el mismo que _Periodo_. Pero, pudiera existir la necesidad de mostrar un numero de cuota en el Checkout (_Periodo_) y enviar un valor diferente al gateway de pago (_Valor a Enviar_). Si tiene dudas, coloque el mismo valor que el campo _Periodo_. |

**Ejemplo de Plan y sus valores**

Promocion a Configurar:
* 1 cuota sin interes
* 2 cuotas sin interes
* 3 cuotas sin interes
* 4 cuotas con 10% de interes
* 5 cuotas con 15% de interes
* 6 cuotas con 20% de interes, mostrar en el Checkout el valor `6`, pero  enviar al gateway el valor de cuota `20`

| Periodo | Coeficiente | TEA | CFT | Valor a Enviar |
|---|---|---|---|---|
| 1 | 1 | 0 | 0 | 1 |
| 2 | 1 | 0 | 0 | 2 |
| 3 | 1 | 0 | 0 | 3 |
| 4 | 1.10 | 0 | 0 | 4 |
| 5 | 1.15 | 0 | 0 | 5 |
| 6 | 1.20 | 0 | 0 | 20 |

**Agregar Plan**
- Click en _Agregar un nuevo Plan_ una nueva fila a la tabla con los campos que deberá completar según lo necesite.
- El campo "Periodo" indica el número de cuotas que se asignarán al pago.
- El campo "Coeficiente" indica el interés correspondiente al número de cuota.
- Los campos "%TEA" y "%CFT" son indicativos, y no son requeridos.
- El campo "Valor a enviar" se utiliza para indicar el número de cuotas que se enviarán a Decidir. Este campo debe tener el mismo valor que el campo "Cuota", a excepción de casos especiales, como al utilizar el emisor de tarjeta "Ahora 12".
​

**Editar Plan**
- En la tabla de promociones, sobre la fila correspondiente, modifique los campos que necesite y presione el botón guardar en la parte inferior del formulario.
​

**Eliminar Plan**
- En la ultima columna de la tabla, sobre la fila correspondiente, presione el link _Eliminar_.
- Luego, haga click en _Guardar_ para persistir los cambios.


#### Consideraciones
Si una Promoción tiene alguna de estas caracteristicas, no se visualizara en el formulario del Checkout:
- Fecha y Hora en los campos _Desde_ y _Hasta_, inexistentes o invalidas
- No posee al menos un Plan
- No posee al menos un dia aplicable
- La Promoción tiene el valor `Deshabilitado` en el campo _Habilitado_


## Technical Notes

### Accounting Library
Checkout Form makes use of `accounting` JS library (already included in WooCommerce) to display the prices in the same way your Store is configured. If you have customized your theme to strip out this lib, form won't work.


# Changelog
## 0.2.4
- **unable to access billing address on guest**; fixes CS trying to extract the `street1` value from a Guest Checkout
- **`establishment_name` length**; fixes so blog name string matches expected max length
- **`security_code` field**; add security_code field during token creation process

## 0.2.3
- **error logs**; improve how some exceptions during place order are displayed

## 0.2.2
### bugfix
- **Credentials**; fix when retrieves production keys, now processes all required configurations for Connector class creation.

## 0.2.1
### bugfix
- **Promotions Admin**; fix how errors are displayed during CRUD operations

## 0.2.0
### improvements
- **Uninstall Hook added**; process will drop custom tables and clean all configuration options previously saved in `wp_options` table. It won't delete existing transaction data already saved within Orders.

- **Checkout form validations**; implements custom validations into checkout form to avoid wrong data being posted to the gateway.

- **`site_transaction_id` value**; now it's built by the WC_Order id and a timestamp value. Avoids *repeated site_transaction_id* error for OmniChannel Merchants using the same `merchant_id` in all the integrations.

### bugfix
- **Localization**; implement missing translations for CRUD admin panel operations in `es_AR`.

- **Order Total now includes fees**; previously if Customer selected an installment with an insterest applied through the coefficient field in Promotions backoffice section, the total sent to the gateway was the one in the Order, instead of the total including the fee.

- **Available Promotions in Checkout**; now takes into account the Applicable Days configured.

## 0.1.0
- Initial version
