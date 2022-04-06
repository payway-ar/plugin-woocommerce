# WC Decidir for WordPress
Plugin para WooCommerce utilizando la plataforma Decidir SPS



### Pre-requisitos

Versión mínima de PHP 7.4
Versión mínima de Wordpress 5.8.3
Versión mínima de WooCommerce 6.0



```
## Instalación

1. Descomprimir el archivo wc-gateway-decidir.zip.
2. Copiar carpeta wc-gateway-decidir al directorio de plugins de wordpress ("raíz de wordpress"/wp-content/plugins).
3. Activar el plugin desde el administrador de WordPress

```

## Configuración

Configurar credenciales y número de comercio

Ingresar al menu de configuración del plugin desde: <em>WooCommerce -> Ajustes -> Pagos </em>
Habilitar el plugin desde el boton <strong>Habilitar</strong>
Presionar el botón </strong> configurar </strong>
Ingresar los datos  solicitados en la pantalla

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


## Menú lateral
En el menú lateral de su Backoffice, se habilitará el grupo "PRISMA PROMOTIONS", donde podrá configurar tarjetas de crédito/débito, bancos y promociones/cuotas.

### Bancos
- En el menú Prisma Decidir > Bancos, podrá administrar los bancos o emisores de tarjetas con los que necesite operar.
-
​
**Añadir banco:**
- En la esquina superior derecha de la tabla de bancos presione en el botón add new/agregar nuevo.
- Ingrese los datos requeridos en el formulario que se desplegará.
- Puede cargar una imagen desde su computadora, y activar o desactivar el banco.
- Presione guardar para aplicar los cambios.
​
**Editar banco:**
- En la tabla de bancos, sobre la fila correspondiente, presione el botón "Editar".
- Se desplegará el mismo formulario del ítem anterior. Aquí puede modificar los datos a su gusto.
​
**Eliminar banco:**
- En la tabla de bancos, sobre la fila correspondiente, presione el botón "Eliminar".
- Se le pedirá confirmar la eliminación del banco.

​
### Tarjetas
- En el menú Prisma Decidir > Tarjetas, podrá administrar los tipos de tarjetas con las que necesite operar.
- Inicialmente, verá una lista con las tarjetas más utilizadas, puede añadir nuevas o eliminar las que desee.
​
**Añadir tarjeta:**
- En la esquina superior derecha de la tabla de tarjetas presione en el botón  add new/agregar nuevo.
- Ingrese los datos requeridos en el formulario que se desplegará.
- Puede cargar una imagen desde su computadora, y activar o desactivar la tarjeta.
- Presione guardar para aplicar los cambios.
​
**Editar tarjeta:**
- En la tabla de tarjetas, sobre la fila correspondiente, presione el botón "Editar".
- Se desplegará el mismo formulario del ítem anterior. Aquí puede modificar los datos a su gusto.
​
**Eliminar tarjeta:**
- En la tabla de tarjetas, sobre la fila correspondiente, presione el botón "Eliminar".
- Se le pedirá confirmar la eliminación de la tarjeta.
​

​
### Promociones
- En el menú Prisma Decidir > Promociones, podrá administrar las promociones y condiciones de cuotas sobre los pedidos realizados.
- Por defecto a todo pedido como mínimo se le asigna 1 cuota. Defina sus cuotas en esta sección según sea necesario.
​
**Añadir promoción:**
- En la esquina superior derecha de la tabla de promociones presione en el botón  add new/agregar nuevo.
- Ingrese los datos requeridos en el formulario que se desplegará.
- Deberá seleccionar las tarjetas y bancos a los que alcanzará la promoción.
- Deberá seleccionar en qué días de la semana esta promoción estará disponible y desde y hasta qué fechas.
- Si su PrestaShop maneja multitienda, deberá seleccionar sobre cuál de ellas ésta promoción será aplicada.
- Elija una posición para darle mayor prioridad a esta promoción.
- Puede activar o desactivar la promoción.
​
**Editar promoción:**
- En la tabla de promociones, sobre la fila correspondiente, presione el botón "Editar".
- Se desplegará el mismo formulario del ítem anterior. Aquí puede modificar los datos a su gusto.
​
**Eliminar promoción:**
- En la tabla de promociones, sobre la fila correspondiente, presione el botón "Eliminar".
- Se le pedirá confirmar la eliminación de la promoción.
​
### Cuotas
- En el mismo formulario de promociones, tendrá disponible una tabla de cuotas.
- Estas cuotas se mostrarán al comprador en caso de que se cumplan las condiciones que hayamos configurado en el ítem anterior.
​
**Añadir cuota:**
- En la esquina superior derecha de la tabla de cuotas presione en el botón con el ícono "+".
- Se añadirá una nueva fila a la tabla con los campos que deberá completar según lo necesite.
- El campo "cuota" indica el número de cuotas que se asignarán al pago.
- El campo "coeficiente" indica el interés correspondiente al número de cuota.
- Los campos "%TEA" y "%CFT" son indicativos, y no son requeridos.
- El campo "Reintegro Bancario" es indicativo de la operatoria del banco una vez realizado el pago.
- En el campo "Descuento", puede asignar una reducción del valor total de la compra.
- El campo "Cuota a enviar" se utiliza para indicar el número de cuotas que se enviarán a Decidir. Este campo debe tener el mismo valor que el campo "Cuota", a excepción de casos especiales, como al utilizar el emisor de tarjeta "Ahora 12".
​
**Editar cuota:**
- En la tabla de promociones, sobre la fila correspondiente, modifique los campos que necesite y presione el botón guardar en la parte inferior del formulario.
​
**Eliminar cuota:**
- En la tabla de cuotas, sobre la fila correspondiente, presione el botón con el ícono del cesto de residuos.
- Se le pedirá confirmar la eliminación de la cuota.


## Technical Notes

### Accounting Library
Checkout Form makes use of `accounting` JS library (already included in WooCommerce) to display the prices in the same way your Store is configured. If you have customized your theme to strip out this lib, form won't work.


# Changelog
## 0.2.4
- **unable to access billing address on guest**; fixes CS trying to extract the `street1` value from a Guest Checkout
- **`establishment_name` length**; fixes so blog name string matches expected max length

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
