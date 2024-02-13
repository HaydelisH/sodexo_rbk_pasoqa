USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_opcionesxtipousuario_obtener]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 22/11/2016
-- Descripcion:   Obtener las opciones según perfil y opción
-- Ejemplo:exec sp_opcionesxtipousuario_obtener 1,1
-- =============================================
CREATE PROCEDURE [dbo].[sp_opcionesxtipousuario_obtener]
@ptipousuarioid   INT,              -- identificador del tipo de usuario o perfil
@popcionid        NVARCHAR(50)      -- codigo opcion menu

      
AS    
BEGIN
      SET NOCOUNT ON;
      
      SELECT 
      consulta,
      modifica,
      elimina,
      crea,
      ver
      FROM opcionesxtipousuario
      WHERE tipousuarioid = @ptipousuarioid
      AND opcionid            = @popcionid
                  
      RETURN
END
GO
