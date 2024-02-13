USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empleados_obtener]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 02/10/2019
-- Descripcion:  Obtener empleado
-- Ejemplo:exec sp_empleados_obtener 'xxxxxxxxxx'
-- =============================================
CREATE PROCEDURE [dbo].[sp_empleados_obtener]
	@pempleadoid NVARCHAR(10)	-- identificador del tipo de usuario         
AS          
BEGIN
	SET NOCOUNT ON;

	SELECT 
		P.personaid as Rut,
		P.nombre + ' ' + P.appaterno + ' ' + p.apmaterno As NombreTrabajador,
		P.nombre,
		P.appaterno,
		P.apmaterno,
		P.personaid,
		CONVERT(CHAR(10),P.fechanacimiento,105)	AS fechanacimiento,
		P.nacionalidad,
		P.direccion,
		P.comuna,
		P.ciudad,
		P.estadocivil As idEstadoCivil,
		EC.Descripcion,
		E.rolid,
		R.Descripcion As DescripcionR,
		P.correo,
		E.idEstadoEmpleado,
		EE.Descripcion As DescripcionEE,
		ROW_NUMBER()Over(Order by P.personaid) As RowNum
	FROM Empleados E 
	INNER JOIN personas P ON E.empleadoid = p.personaid
	LEFT JOIN Roles R ON E.rolid = R.rolid
	LEFT JOIN EstadoCivil EC ON P.estadocivil = EC.idEstadoCivil
	LEFT JOIN EstadosEmpleados EE ON E.idEstadoEmpleado = EE.idEstadoEmpleado
    WHERE P.personaid = @pempleadoid
                
    RETURN
END
GO
