USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_fichas_obtener_pendiente]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Cristian Soto  
-- Creado el: 08/11/2018
-- Descripcion:   Obtener una ficha x rut pendiente
-- Ejemplo:exec sp_fichas_obtener_pendiente
-- =============================================
CREATE PROCEDURE [dbo].[sp_fichas_obtener_pendiente]
      @pempleadoid           nvarchar(10)
AS    
BEGIN
      SET NOCOUNT ON;
      
      SELECT 
           empleadoid
      FROM fichas f
            
      WHERE  f.empleadoid = @pempleadoid
      and (f.estadoid = 1 or f.estadoid = 2)--pendiente o ducumento generado
               
      RETURN;
END
GO
