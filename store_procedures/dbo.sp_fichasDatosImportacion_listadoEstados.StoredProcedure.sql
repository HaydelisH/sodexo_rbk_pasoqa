USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichasDatosImportacion_listadoEstados]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		Haydelis Hernández
-- Create date: 05-07-2019
-- Description:	Listado de fichasDatosImportacion
-- Ejemplo: sp_sp_fichasDatosImportacion_listadoEstados 0,0,'',1,10,'26131316-2',1
-- =============================================
CREATE PROCEDURE [dbo].[sp_fichasDatosImportacion_listadoEstados]
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	SELECT 
		estadoid,
		nombreestado,
		estadoid As idEstado,
		nombreestado As Estado
	FROM 
		EstadosFichas
		               
	RETURN;
END
GO
