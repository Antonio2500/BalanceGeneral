class CuentaContable:
    def __init__(self, nombre, tipo_cuenta):
        self.nombre = nombre
        self.transacciones = []
        self.tipo_cuenta = tipo_cuenta
    
    def agregar_transaccion(self, montos_debe, montos_haber):
        if len(montos_debe) != len(montos_haber):
            print("La cantidad de montos en debe y haber no coincide.")
            return

        for debe, haber in zip(montos_debe, montos_haber):
            self.transacciones.append((debe, haber))
    
    def obtener_saldo(self):
        saldo = 0
        for transaccion in self.transacciones:
            debe, haber = transaccion[0], transaccion[1]
            if self.tipo_cuenta == 1:  # Activo
                saldo += debe - haber
            elif self.tipo_cuenta == 2:  # Pasivo
                saldo += haber - debe
            elif self.tipo_cuenta == 3:  # Ventas
                saldo += haber - debe
            elif self.tipo_cuenta == 4:  # Costo de Ventas
                saldo += debe - haber
            elif self.tipo_cuenta == 5:  # Gastos de Operación
                saldo += haber - debe
        return saldo

def esquema_de_mayor_contable():
    cuentas = {}
    tipos_cuenta = {
        1: "Activo",
        2: "Pasivo",
        3: "Ventas",
        4: "Costo de Ventas",
        5: "Gastos de Operación"
    }
    continuar = True

    while continuar:
        cuenta_nombre = input("Ingrese el nombre de la cuenta: ")
        print("Seleccione el tipo de cuenta:")
        for num, tipo in tipos_cuenta.items():
            print(f"{num}. {tipo}")
        tipo_cuenta = int(input("Opción: "))

        montos_debe = []
        montos_haber = []

        num_montos = int(input("Ingrese la cantidad de montos: "))

        for i in range(num_montos):
            debe = float(input("Ingrese el monto en debe: "))
            haber = float(input("Ingrese el monto en haber: "))

            montos_debe.append(debe)
            montos_haber.append(haber)

        if cuenta_nombre not in cuentas:
            cuentas[cuenta_nombre] = CuentaContable(cuenta_nombre, tipo_cuenta)
        
        cuenta = cuentas[cuenta_nombre]
        cuenta.agregar_transaccion(montos_debe, montos_haber)

        respuesta = input("¿Desea ingresar otra transacción? (s/n): ")
        if respuesta.lower() != "s":
            continuar = False

    # Calcular los saldos de cada cuenta
    resultados = []
    movimientos_deudores = 0
    movimientos_acreedores = 0
    saldos_deudores = 0
    saldos_acreedores = 0

    movimientos_deudores_proveedor = 0
    # Balanza de movimientos
    for cuenta_nombre, cuenta in cuentas.items():
        saldo = abs(cuenta.obtener_saldo())
        resultados.append("Cuenta: {}\nTipo: {}\nSaldo: {}\n".format(cuenta_nombre, tipos_cuenta[cuenta.tipo_cuenta], saldo))

        if cuenta.tipo_cuenta == 1:  # Activo
            movimientos_deudores += sum([t[0] for t in cuenta.transacciones])
            movimientos_acreedores += sum([t[1] for t in cuenta.transacciones])
        
        if cuenta.tipo_cuenta == 2:  # Pasivo
            movimientos_deudores += sum([t[0] for t in cuenta.transacciones])
            movimientos_acreedores += sum([t[1] for t in cuenta.transacciones])
            movimientos_deudores_proveedor = sum([t[1] for t in cuenta.transacciones])
        
        if cuenta.tipo_cuenta == 3:  # Ventas
            movimientos_deudores += sum([t[0] for t in cuenta.transacciones])
            movimientos_acreedores += sum([t[1] for t in cuenta.transacciones])
        
        if cuenta.tipo_cuenta == 4:  # Costo de Ventas
            movimientos_deudores += sum([t[0] for t in cuenta.transacciones])
            movimientos_acreedores += sum([t[1] for t in cuenta.transacciones])
        
        if cuenta.tipo_cuenta == 5:  # Gastos de Operación
            movimientos_deudores += sum([t[0] for t in cuenta.transacciones])
            movimientos_acreedores += sum([t[1] for t in cuenta.transacciones])

    # Balanza de saldos
    saldo_ventas = 0
    saldo_costo_ventas = 0
    saldo_gastos_operacion = 0

    # Activo circulante
    saldo_bancos = 0
    saldo_clientes = 0
    saldo_almacen = 0

    # Activo fijo
    saldo_equipo_oficina = 0
    
    # Pasivo a corto plazo
    saldo_proveedores = 0

    # Capital
    saldo_capital_social = 0
    saldo_utilidad_del_ejericio = 0

    for cuenta_nombre, cuenta in cuentas.items():
        saldo = cuenta.obtener_saldo()
       
        if cuenta.tipo_cuenta == 1:  # Activo
            if cuenta_nombre.lower() == "bancos":
                saldo_bancos = max(abs(saldo), 0)
            if cuenta_nombre.lower() == "clientes":
                saldo_clientes = max(abs(saldo), 0)
            if cuenta_nombre.lower() == "almacenes":
                saldo_almacen = max(abs(saldo), 0)
            if cuenta_nombre.lower() == "equipo de oficina":
                saldo_equipo_oficina = max(abs(saldo), 0)   
            if cuenta_nombre.lower() == "deudores diversos":
                saldo_equipo_oficina = max(abs(saldo), 0)  
            if cuenta_nombre.lower() == "documentos por cobrar":
                saldo_equipo_oficina = max(abs(saldo), 0)  

            saldos_deudores += max(abs(saldo), 0)
        
        if cuenta.tipo_cuenta == 2:  # Pasivo
            if cuenta_nombre.lower() == "proveedores":
                saldo_proveedores = movimientos_deudores_proveedor - max(abs(saldo), 0) 
            if cuenta_nombre.lower() == "capital social":
                saldo_capital_social = max(abs(saldo), 0) 

            saldos_acreedores += max(abs(saldo), 0)

        if cuenta.tipo_cuenta == 3:  # Ventas
            if not saldo_ventas:  
                saldo_ventas = max(abs(saldo), 0)

            saldos_acreedores += max(abs(saldo), 0)
            
        if cuenta.tipo_cuenta == 4:  # Costo de Ventas
            if not saldo_costo_ventas:  
                saldo_costo_ventas = max(abs(saldo), 0)

            saldos_deudores += max(abs(saldo), 0)

        if cuenta.tipo_cuenta == 5:  # Gastos de Operación
            if not saldo_gastos_operacion:  
                saldo_gastos_operacion = max(abs(saldo), 0)

            saldos_deudores += max(abs(saldo), 0)

    # Balanza de movimientos
    resultados.append("Balanza de Movimientos\n")
    resultados.append("Total Movimientos Deudores: {}\n".format(movimientos_deudores))
    resultados.append("Total Movimientos Acreedores: {}\n".format(movimientos_acreedores))

    # Balanza de saldos
    resultados.append("Balanza de Saldos\n")
    resultados.append("Total Saldos Deudores: {}\n".format(saldos_deudores))
    resultados.append("Total Saldos Acreedores: {}\n".format(saldos_acreedores))

    # Pedir al usuario si desea un estado de resultados
    desea_estado_resultados = input("¿Desea generar un estado de resultados? (s/n): ")

    utilidad_bruta = 0
    gastos_operacion = 0

    utilidad_bruta = saldo_ventas - saldo_costo_ventas
    gastos_operacion = utilidad_bruta - saldo_gastos_operacion
    
    # Si se desea un estado de resultados
    if desea_estado_resultados.lower() == 's':
    # Pedir al usuario los detalles para imprimir
        nombre_completo = input("Ingrese su nombre completo: ")
        fecha = input("Ingrese la fecha: ")

        # Imprimir el estado de resultados
        print("Nombre completo: ", nombre_completo.title())
        print("Fecha: ", fecha)
        print("Venta: ", saldo_ventas)
        print("Costo de ventas: ", saldo_costo_ventas)
        print("Utilidad bruta: ", utilidad_bruta)
        print("Gastos de operación: ", gastos_operacion)

    # Pedir al usuario si desea un balance general
    desea_balance_general = input("¿Desea generar un balance general? (s/n): ")

    Total_Activo_Circulante = 0
    Total_Activo_Fijo = 0
    Total_Activo = 0
    Total_Pasivo_Corto_Plazo = 0
    Total_capital = 0
    Total_patrimonio_neto = 0

    # Si se desea un balance general
    if desea_balance_general.lower() == 's':
        print("Activo Circulante")
        print("\u2022 Bancos: ", saldo_bancos)
        print("\u2022 Clientes: ", saldo_clientes)
        print("\u2022 Almacén: ", saldo_almacen)
        Total_Activo_Circulante = saldo_bancos + saldo_clientes + saldo_almacen
        print("Total Activo Circulante: ", Total_Activo_Circulante)

        print("Activo Fijo")
        print("\u2022 Equipo de oficina: ", saldo_equipo_oficina)
        Total_Activo_Fijo = saldo_equipo_oficina
        print("Total Activo Fijo: ", Total_Activo_Fijo)
        Total_Activo = Total_Activo_Circulante + Total_Activo_Fijo
        print("Total Activo: ", Total_Activo)

        print("Pasivo a Corto Plazo")
        print("\u2022 Costo de ventas: ", saldo_costo_ventas)
        print("\u2022 Proveedores: ", saldo_proveedores)
        Total_Pasivo_Corto_Plazo = saldo_costo_ventas + saldo_proveedores
        print("Total Pasivo a Corto Plazo: ", Total_Pasivo_Corto_Plazo)

        print("Capital")
        saldo_utilidad_del_ejericio = gastos_operacion
        print("\u2022 Utilidad del ejercicio: ", saldo_utilidad_del_ejericio)
        saldo_capital_social = (Total_Activo - Total_Pasivo_Corto_Plazo) - gastos_operacion
        print("\u2022 Capital Social: ", saldo_capital_social)
        Total_capital = saldo_capital_social + saldo_utilidad_del_ejericio
        print("Total Capital: ", Total_capital)
        Total_patrimonio_neto = Total_Pasivo_Corto_Plazo + Total_capital
        print("Patrimonio Neto: ", Total_patrimonio_neto)

    # Guardar los resultados en un archivo
    nombre_archivo = input("Ingrese el nombre del archivo de salida: ")
    nombre_archivo = nombre_archivo + ".txt" if not nombre_archivo.endswith(".txt") else nombre_archivo
    with open(nombre_archivo, 'w') as archivo:
        archivo.writelines(resultados)

    print("Archivo generado con éxito.")

# Ejecutar la función
esquema_de_mayor_contable()
