USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_obtener_DocumentosObligatorios]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernández 
-- Creado el: 11-07-2019
-- Descripcion: Consulta los documentos obligatorios que les falta subir 
-- Ejemplo:exec sp_fichas_obtener_DocumentosObligatorios
-- =============================================
CREATE PROCEDURE [dbo].[sp_fichas_obtener_DocumentosObligatorios]
     @pidFicha INT
AS    
BEGIN
      SET NOCOUNT ON;
      
      DECLARE @tipoSubida INT
      DECLARE @Obligatorio INT
      DECLARE @CantidadObligatorios	INT
      DECLARE @Subidos INT
      
      SET @tipoSubida = 1 --Subida al Gestor 
      SET @Obligatorio = 1 --Obligatorio
      
      SELECT 
		@Subidos = COUNT(FD.documentoid)
	  FROM 
		ChecklistDocumentos CL 
      LEFT JOIN [Smu_Gestor].[dbo].[documentosinfo] DI ON CL.idTipoGestor = DI.tipodocumentoid
      LEFT JOIN fichasdocumentos FD ON DI.documentoid = FD.documentoid AND FD.idTipoSubida = @tipoSubida AND fd.fichaid = @pidFicha
	  WHERE cl.Obligatorio = @Obligatorio
	  
	  SELECT @CantidadObligatorios = COUNT(*)FROM ChecklistDocumentos WHERE Obligatorio = @Obligatorio
	  
	  SELECT @Subidos As Subidos, @CantidadObligatorios As Obligatorios
	  
      RETURN;
END
GO
