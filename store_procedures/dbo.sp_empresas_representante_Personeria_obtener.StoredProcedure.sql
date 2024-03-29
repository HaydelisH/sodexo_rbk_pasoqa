USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_empresas_representante_Personeria_obtener]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Macarena Parra Bruna
-- Creado el: 14/06/2018
-- Descripcion: Obtener representante
-- Ejemplo:exec sp_empresas_representantes_obtener'22604213-K' 
-- =============================================
CREATE PROCEDURE [dbo].[sp_empresas_representante_Personeria_obtener]
	@RutEmpresa VARCHAR (10),
	@RutUsuario VARCHAR (10)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	IF EXISTS (SELECT RutUsuario FROM Firmantes WHERE RutUsuario = @RutUsuario AND RutEmpresa=@RutEmpresa)
     BEGIN
		SELECT 
		Firmantes.RutUsuario,
		Firmantes.RutEmpresa,
		personas.nacionalidad,
		personas.nombre,
		personas.appaterno,
		personas.apmaterno,
		F.idFirma,
		F.Descripcion,
		personas.correo,
		Firmantes.idCargo,
		Firmantes.Cargo
		FROM Firmantes 
		INNER JOIN personas ON personas.personaid = Firmantes.RutUsuario
		INNER JOIN Cargos C ON C.idCargo = Firmantes.idCargo
		INNER JOIN Usuarios U ON U.usuarioid = personas.personaid
		INNER JOIN Firmas F on F.idFirma = U.idFirma
		WHERE Firmantes.RutEmpresa = @RutEmpresa 
		AND RutUsuario = @RutUsuario 
		RETURN
	END  
		 
END
GO
