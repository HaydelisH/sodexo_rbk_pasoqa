USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_firmantes_obtener]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_firmantes_obtener]
	@RutFirmante VARCHAR(10),
	@RutEmpresa VARCHAR(10)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
			
    -- Insert statements for procedure here
    BEGIN
		--Consultar existe la palabra Notario en el registro 			
		SELECT 
			UPPER(RutUsuario) As RutUsuario,
			RutEmpresa,
			tienerol,
			idCargo
		FROM 
			Firmantes
		WHERE
			RutUsuario = @RutFirmante 
		AND
			RutEmpresa = @RutEmpresa  
	END 
END
GO
