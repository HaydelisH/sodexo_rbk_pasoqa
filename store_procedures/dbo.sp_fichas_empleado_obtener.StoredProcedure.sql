USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_empleado_obtener]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_fichas_empleado_obtener]
	@fichaid INT
AS
BEGIN
	SET NOCOUNT ON;
   
	BEGIN
        SELECT 
			f.RutTrabajador as personaid,
			f.NombreTrabajador as nombre_trabajador,
			f.ApPatTrabajador as appaterno_trabajador,
			f.ApMatTrabajador as apmaterno_trabajador,
			isnull(f.NombreTrabajador,'') + ' ' + isnull(f.ApPatTrabajador,'') + ' ' + isnull(f.ApMatTrabajador,'') as NombreTrabajador,
			f.Nacionalidad as nacionalidad,
			f.CorreoElectronicoEmpleado as correo,
			CONVERT(char(10), f.FechaNacimiento, 105) AS fechanacimiento,
			f.Direccion as direccion,
			f.Comuna as comuna,
			f.CiudadTrabajador as ciudad,
			f.EstadoCivil AS estadoCivil,
			f.RolEmpleado as Rol,
			CASE f.EstadoCivil	
				WHEN 1 THEN 'Soltero(a)'
				WHEN 2 THEN 'Casado(a)'
				WHEN 3 THEN 'Divorciado(a)'
				WHEN 4 THEN 'Viudo(a)'
			END AS Estado,
            CargosEmpleado.Descripcion AS cargoEmpleado
        FROM fichasDatosImportacion f
        INNER JOIN EstadoCivil
            ON EstadoCivil.idEstadoCivil = f.EstadoCivil
        INNER JOIN CargosEmpleado
            ON CargosEmpleado.idCargoEmpleado = f.CodCargo
        WHERE f.fichaid = @fichaid
    END;
END;
GO
