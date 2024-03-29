USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_personas_obtenerNombre_20211115_HH]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 06/14/2018
-- Descripcion: Obtiene el nombre de persona
-- Modificado: gdiaz 11/04/2021
-- Ejemplo:exec sp_personas_obtenerNombre
-- =============================================
CREATE PROCEDURE [dbo].[sp_personas_obtenerNombre_20211115_HH]
	@personaid VARCHAR (10)
AS
BEGIN
	
	DECLARE @dato int;
	if exists (SELECT * from usuarios where usuarioid=@personaid)
		begin
			set @dato = 1;
		end
	else
		begin
			set @dato = 0;
		end;
    SELECT 
		p.personaid, 
		isnull(p.nombre,'') as nombre, 
		isnull(p.appaterno,'') as appaterno, 
		isnull(p.apmaterno,'') as apmaterno,
        isnull(p.nombre,'') + ' ' + isnull(p.appaterno,'') + ' ' + isnull(p.apmaterno,'') AS nombreCompleto,
		p.correo,
		p.nacionalidad,
		p.direccion,
		p.comuna,
		p.ciudad,
		CONVERT(CHAR(10), p.fechanacimiento,105) As fechanacimiento,
		p.estadocivil,
		e.rolid,
		u.idFirma,
		f.Descripcion,
		p.fono, 
		e.idEstadoEmpleado,
		@dato as existe
		--cdv.CentroCosto
	FROM personas p 
	LEFT JOIN Empleados e on e.empleadoid = p.personaid
	LEFT JOIN Usuarios u on p.personaid = u.usuarioid
	LEFT JOIN Firmas f on u.idFirma = f.idFirma
	--INNER JOIN ContratoDatosVariables CDV on p.personaid = CDV.Rut
	 WHERE personaid = @personaid AND Eliminado=0
                         
    RETURN                                                             

END
GO
