USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empresas_representantes_SinNotarios_20210920]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 14/06/2018
-- Descripcion: Obtener representantes
-- Ejemplo:exec sp_empresas_representantes'22604213-K' 
-- =============================================
CREATE PROCEDURE [dbo].[sp_empresas_representantes_SinNotarios_20210920]
	@RutEmpresa VARCHAR (10)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	IF EXISTS (SELECT RutEmpresa FROM Empresas WHERE RutEmpresa = @RutEmpresa AND Eliminado = 0 )
	BEGIN
		SELECT 
			personas.personaid,
			REPLACE(REPLACE(REPLACE(REPLACE(REPLACE( isnull(personas.nombre,'') + ' ' + isnull(personas.appaterno,'') + ' ' + isnull(personas.apmaterno,''), 'á', 'a'), 'é','e'), 'í', 'i'), 'ó', 'o'), 'ú','u') as nombrecompleto,
			personas.nombre,
			personas.apmaterno, 
			personas.appaterno,
			c.descripcion,
			c.idCargo,
			f.Descripcion as firma
		FROM Firmantes 
		INNER JOIN personas ON personas.personaid = Firmantes.RutUsuario
		INNER JOIN Cargos C ON C.idCargo = Firmantes.idCargo
		INNER JOIN Usuarios U ON U.usuarioid = personas.personaid
		INNER JOIN Firmas F on F.idFirma = U.idFirma
		WHERE Firmantes.RutEmpresa = @RutEmpresa --AND c.idCargo <> 2
		RETURN
	END   
END
GO
