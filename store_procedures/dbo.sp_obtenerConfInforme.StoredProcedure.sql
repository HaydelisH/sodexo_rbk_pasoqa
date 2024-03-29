USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_obtenerConfInforme]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:        RC
-- Create date: 20200121
-- Description:   sp_obtenerConfInforme 1
-- Obtiene la configuracion para envio del Correo
-- si Adjunta es True, se adjuntara Excel con resultado del 
-- SP indicado en campo SP
-- =============================================
CREATE PROCEDURE [dbo].[sp_obtenerConfInforme]
@CodInforme int
AS
BEGIN
      SET NOCOUNT ON;

            SELECT [CodInforme]
      ,[SP]
      ,[CodCorreo]
      ,[Adjunta]
      ,[Grupo]
      FROM [dbo].[CorreoInformes]
      where [CodInforme] = @CodInforme
END
GO
