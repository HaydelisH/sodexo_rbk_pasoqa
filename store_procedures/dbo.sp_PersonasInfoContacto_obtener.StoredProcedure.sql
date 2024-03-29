USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_PersonasInfoContacto_obtener]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: RC
-- Creado el: 12/07/2018
-- Descripcion: Muestra el listado de de Documetnos Generados 
-- [sp_PersonasInfoContacto_obtener] 'xxxxx'
-- =============================================
CREATE PROCEDURE [dbo].[sp_PersonasInfoContacto_obtener]
	@ppersonaid     varchar(10)    -- Id Persona
AS
BEGIN
             
		SELECT    
			  PIC.[personaid]
			 ,P.nombre
			 ,PIC.[direccion]
			 ,PIC.[comuna]
			 ,PIC.[ciudad]
			 ,PIC.[celularContacto]
			 ,PIC.[celularPersonal]
			 ,PIC.[envioinfo]
			 ,CASE envioinfo WHEN 1 THEN 'Si' ELSE 'No' END ei
			 ,PIC.[nombreContacto]
			 ,PIC.[relacionContacto]
			 ,ROW_NUMBER()Over(Order by PIC.[personaid]) As RowNum
		FROM [personaInfoContacto] PIC
		INNER JOIN Personas P on P.personaid = PIC.personaid
		WHERE PIC.personaid = @ppersonaid
		
   RETURN                                                             

END


/****** Object:  StoredProcedure [dbo].[sp_PersonasInfoContacto_listado]    Script Date: 06/30/2020 14:51:23 ******/
SET ANSI_NULLS ON
GO
