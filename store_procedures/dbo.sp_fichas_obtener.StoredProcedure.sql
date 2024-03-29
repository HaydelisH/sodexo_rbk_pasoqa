USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_obtener]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez	
-- Creado el: 04/10/2018
-- Descripcion:	Obtener una ficha 
-- Ejemplo:exec sp_fichas_obtener 
-- =============================================
CREATE PROCEDURE [dbo].[sp_fichas_obtener]
	@fichaid            INT
AS	
BEGIN
	SET NOCOUNT ON;
	
	 SELECT 
		p.personaid, 
		p.nombre, 
		p.appaterno, 
		p.apmaterno, 
		p.correo,
		p.nacionalidad,
		p.direccion,
		p.comuna,
		p.ciudad,
		CONVERT(CHAR(10), p.fechanacimiento,105) As fechanacimiento,
		p.estadocivil As idEstadoCivil,
		u.idFirma,
		f.Descripcion,
		p.fono,
		e.RutEmpresa,
		e.RazonSocial,
		cc.centrocostoid,
		cc.nombrecentrocosto,
		fi.fichaid,
		CONVERT(CHAR(10),fi.fechasolicitud,105) + ' ' + CONVERT(CHAR(10),fi.fechasolicitud,108) As fechacreacion,
		fi.estadoid,
		es.nombreestado,
		es.nombreestado
	FROM fichas fi 
		INNER JOIN personas p on fi.empleadoid = p.personaid
		INNER JOIN Usuarios u on p.personaid = u.usuarioid
		INNER JOIN Firmas f on u.idFirma = f.idFirma
		INNER JOIN Empresas e on fi.empresaid = e.RutEmpresa
		INNER JOIN centroscosto cc on fi.centrocostoid = cc.centrocostoid AND fi.empresaid = cc.empresaid
		--INNER JOIN lugarespago l on fi.lugarpagoid = l.lugarpagoid and l.empresaid = fi.empresaid
		INNER JOIN EstadosFichas es on fi.estadoid = es.estadoid
	WHERE fi.fichaid = @fichaid 
	
	RETURN;
END
GO
