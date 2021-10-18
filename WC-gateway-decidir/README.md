# dec_WooCommerce

Plugin para WooCommerce utilizando la plataforma Decidir SPS



### Pre-requisitos 📋

Versión mínima de PHP 5.6
Versión mínima de Wordpress 5.3
Versión mínima de Woocmmerce 4.6



```

## Instalación 🔧

1. Descomprimir el archivo WC-gateway-decidir.zip. 
2. Copiar carpeta wWC-gateway-decidir al directorio de plugins de wordpress ("raíz de wordpress"/wp-content/plugins). 
3. Activar el plugin desde el adminsitrador de wordpress

```

## Configuración 📦

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

