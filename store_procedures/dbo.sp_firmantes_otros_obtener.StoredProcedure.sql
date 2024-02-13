USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_firmantes_otros_obtener]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_firmantes_otros_obtener]
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
			UPPER(E.empleadoid) As RutUsuario,
			E.empresaid,
			E.rolid,
			P.Nombre
		FROM [Sodexo_Gestor].[dbo].[empleados] E
		INNER JOIN [Sodexo_Gestor].[dbo].[personas] P on personaid = @RutFirmante 
		WHERE
			empleadoid = @RutFirmante 
		
	END 
END
GO
